<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\User\UserAddressResource as UserUserAddressResource;
use App\Models\AddressUser;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class AddressController extends Controller
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
     * @param Filter $filter
     * @param User $user
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter, User $user): AnonymousResourceCollection
    {
        Gate::authorize('view', $user);
        return AddressResource::collection($this->service->getUserAddresses(user: $user, filter: $filter));
    }

    /**
     * @param StoreAddressRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(StoreAddressRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        if (!$this->service->canCreateAddress($user->id)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'تعداد مجاز ثبت آدرس به حداکثر آن رسیده است.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        $model = $this->service->createAddress($validated + ['user_id' => $user->id]);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد آدرس با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد آدرس',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param UpdateAddressRequest $request
     * @param User $user
     * @param AddressUser $address
     * @return UserUserAddressResource|JsonResponse
     */
    public function update(
        UpdateAddressRequest $request,
        User                 $user,
        AddressUser          $address,
    ): UserUserAddressResource|JsonResponse
    {
        $validated = $request->validated();
        $model = $this->service->updateUserAddressByUserIdAndId($user->id, $address->id, $validated);

        if (!is_null($model)) {
            return new UserUserAddressResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش آدرس',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param User $user
     * @param AddressUser $address
     * @return JsonResponse
     */
    public function destroy(User $user, AddressUser $address): JsonResponse
    {
        $res = $this->service->deleteAddressByUserIdAndId($user->id, $address->id);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
