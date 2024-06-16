<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserOrderDetailRequest;
use App\Http\Resources\User\UserOrderResource;
use App\Http\Resources\User\UserOrderSingleResource;
use App\Http\Resources\User\UserUnpaidOrderPaymentsResource;
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
    public function unpaidOrderPayments(Request $request): AnonymousResourceCollection
    {
        return UserUnpaidOrderPaymentsResource::collection($this->service->getUserUnpaidOrderPayments(
            $request->user()
        ));
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function latest(Request $request): AnonymousResourceCollection
    {
        return UserOrderResource::collection($this->service->getUserLatestOrders(
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
     * @param OrderDetail $order
     * @return UserOrderSingleResource
     */
    public function show(OrderDetail $order): UserOrderSingleResource
    {
        return new UserOrderSingleResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserOrderDetailRequest $request
     * @param OrderDetail $order
     * @return UserOrderSingleResource|JsonResponse
     */
    public function update(
        UpdateUserOrderDetailRequest $request,
        OrderDetail $order
    ): UserOrderSingleResource|JsonResponse
    {
        $validated = filter_validated_data($request->validated(), [
            'address',
            'postal_code',
            'receiver_name',
            'receiver_mobile'
        ]);

        $model = $this->service->updateById($order->id, $validated);

        if (!is_null($model)) {
            return new UserOrderSingleResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش اطلاعات سفارش',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
