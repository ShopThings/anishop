<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Enums\Settings\SettingGroupsEnum;
use App\Enums\Settings\SettingsEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Models\User;
use App\Services\Contracts\SettingServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class SettingController extends Controller
{
    /**
     * @param SettingServiceInterface $service
     */
    public function __construct(
        protected SettingServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param string|null $group
     * @return JsonResponse|AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request, ?string $group = null): JsonResponse|AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $groupName = SettingGroupsEnum::tryFrom($group);
        if (!is_null($group) && is_null($groupName)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'گروه تنظیمات درخواست شده نامعتبر می‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (is_null($groupName)) {
            $settings = $this->service->getSettings();
        } else {
            $settings = $this->service->getSettingByGroupName($groupName);
        }

        return SettingResource::collection($settings);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request)
    {
        $this->authorize('update', User::class);

        $validated = $request->validated();

        // update setting of each setting's key and track update and failed results
        $updatesCount = 0;
        $failedCount = 0;
        foreach ([
                     SettingsEnum::TITLE,
                     SettingsEnum::DESCRIPTION,
                     SettingsEnum::KEYWORDS,
                     SettingsEnum::SMS_SIGNUP,
                     SettingsEnum::SMS_ACTIVATION,
                     SettingsEnum::SMS_RECOVER_PASS,
                     SettingsEnum::SMS_BUY,
                     SettingsEnum::SMS_ORDER_STATUS,
                     SettingsEnum::SMS_RETURN_ORDER,
                     SettingsEnum::SMS_RETURN_ORDER_STATUS,
                     SettingsEnum::ADDRESS,
                     SettingsEnum::PHONES,
                     SettingsEnum::STORE_PROVINCE,
                     SettingsEnum::STORE_CITY,
                     SettingsEnum::MIN_FREE_POST_PRICE,
                     SettingsEnum::PRODUCT_EACH_PAGE,
                     SettingsEnum::BLOG_EACH_PAGE,
                     SettingsEnum::SOCIALS,
                     SettingsEnum::FOOTER_DESCRIPTION,
                     SettingsEnum::FOOTER_COPYRIGHT,
                     SettingsEnum::FOOTER_NAMADS,
                     SettingsEnum::DEFAULT_POST_PRICE
                 ] as $name) {
            if ($this->_updateSetting($name, $validated)) $updatesCount++;
            else $failedCount++;
        }

        // set lat_lng separately because we get values separately
        if (isset($validated['latitude'], $validated['longitude'])) {
            if ($this->service->updateByName(SettingsEnum::LAT_LNG->value, [
                'setting_value' => [$validated['latitude'], $validated['longitude']],
            ])) $updatesCount++;
            else $failedCount++;
        }

        // check result and return appropriate response

        if ($updatesCount === 0) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش تنظیمات',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($failedCount > 0) {
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'برخی از تنظیمات ثبت نشد!',
            ], ResponseCodes::HTTP_OK);
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'ویرایش تنظیمات انجام شد.',
        ], ResponseCodes::HTTP_OK);
    }

    /**
     * @param SettingsEnum $settingName
     * @param mixed $values
     * @return bool
     */
    private function _updateSetting(SettingsEnum $settingName, mixed $values): bool
    {
        if (isset($values[$settingName->value])) {
            return $this->service->updateByName($settingName->value, [
                'setting_value' => $values[$settingName->value],
            ]);
        }
        return false;
    }
}
