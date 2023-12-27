<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserOrderDetailRequest;
use App\Http\Resources\User\UserOrderResource;
use App\Http\Resources\User\UserOrderSingleResource;
use App\Models\OrderDetail;
use App\Services\Contracts\OrderServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserOrderController extends Controller
{
    /**
     * @param OrderServiceInterface $service
     */
    public function __construct(
        protected OrderServiceInterface $service
    )
    {
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function latest(Request $request): AnonymousResourceCollection
    {
        return UserOrderResource::collection($this->service->getLatestUserOrders(
            userId: $request->user()->id,
            limit: 5
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, Filter $filter): AnonymousResourceCollection
    {
        return UserOrderResource::collection($this->service->getOrders(
            userId: $request->user()->id,
            filter: $filter
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param OrderDetail $orderDetail
     * @return UserOrderSingleResource
     */
    public function show(OrderDetail $orderDetail): UserOrderSingleResource
    {
        return new UserOrderSingleResource($orderDetail);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserOrderDetailRequest $request
     * @param OrderDetail $orderDetail
     * @return UserOrderSingleResource|JsonResponse
     */
    public function update(
        UpdateUserOrderDetailRequest $request,
        OrderDetail                  $orderDetail
    ): UserOrderSingleResource|JsonResponse
    {
        $validated = $request->validated([
            'province', 'city', 'address', 'postal_code',
            'receiver_name', 'receiver_mobile'
        ]);

        $model = $this->service->updateById($orderDetail->id, $validated);

        if (!is_null($model)) {
            return new UserOrderSingleResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش اطلاعات سفارش',
        ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
