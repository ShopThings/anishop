<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\User\UserAddressResource as UserUserAddressResource;
use App\Models\AddressUser;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserAddressController extends Controller
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filter = new Filter($request);
        $filter->reset()->setOrder(['id' => 'asc']);

        return UserUserAddressResource::collection($this->service->getUserAddresses(
            user: $request->user(),
            filter: $filter
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAddressRequest $request
     * @return JsonResponse
     */
    public function store(StoreAddressRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $request->user();

        if (!$this->service->canCreateAddress($user->id)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'تعداد مجاز ثبت آدرس به حداکثر آن رسیده است.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        $model = $this->service->createAddress([
                'user_id' => $user->id,
            ] + $validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد آدرس با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد آدرس',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param AddressUser $addressUser
     * @return UserUserAddressResource
     */
    public function show(AddressUser $addressUser): UserUserAddressResource
    {
        return new UserUserAddressResource($addressUser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAddressRequest $request
     * @param AddressUser $addressUser
     * @return UserUserAddressResource|JsonResponse
     */
    public function update(
        UpdateAddressRequest $request,
        AddressUser          $addressUser
    ): UserUserAddressResource|JsonResponse
    {
        $validated = $request->validated();
        $model = $this->service->updateUserAddressByUserIdAndId($request->user()->id, $addressUser->id, $validated);

        if (!is_null($model)) {
            return new UserUserAddressResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش آدرس',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param AddressUser $addressUser
     * @return JsonResponse
     */
    public function destroy(Request $request, AddressUser $addressUser): JsonResponse
    {
        $res = $this->service->deleteAddressByUserIdAndId($request->user()->id, $addressUser->id);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
