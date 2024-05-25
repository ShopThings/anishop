<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Resources\UserResource;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserSpecificationController extends Controller
{
    /**
     * @param UserServiceInterface $service
     */
    public function __construct(
        protected UserServiceInterface $service
    )
    {
    }

    /**
     * @param UpdateUserInfoRequest $request
     * @return JsonResponse
     */
    public function updateInfo(UpdateUserInfoRequest $request): JsonResponse
    {
        $validated = $request->validated(['first_name', 'last_name', 'national_code', 'sheba_number']);

        $user = $request->user();

        if (trim($user->first_name ?? '') !== '') {
            unset($validated['first_name']);
        }
        if (trim($user->last_name ?? '') !== '') {
            unset($validated['last_name']);
        }
        if (trim($user->national_code ?? '') !== '') {
            unset($validated['national_code']);
        }

        if (empty($validated)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'اطلاعات با موفقیت ویرایش شد.',
            ]);
        }

        $model = $this->service->updateById($user->id, $validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => new UserResource($model),
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش اطلاعات! لطفا دوباره تلاش نمایید.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param UpdateUserPasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdateUserPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated(['password']);

        $user = $request->user();

        $model = $this->service->updateById($user->id, $validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'کلمه عبور با موفقیت تغییر یافت.',
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش کلمه عبور! لطفا دوباره تلاش نمایید.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
