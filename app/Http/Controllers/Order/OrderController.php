<?php

namespace App\Http\Controllers\Order;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\OrderFilter;
use App\Http\Requests\UpdateOrderDetailRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderDetailSingleResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Services\Contracts\OrderServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class OrderController extends Controller
{
    /**
     * @param OrderServiceInterface $service
     */
    public function __construct(
        protected OrderServiceInterface $service
    )
    {
        $this->policyModel = Order::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @param User|null $user
     * @return AnonymousResourceCollection
     */
    public function index(OrderFilter $filter, ?User $user = null): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Order::class);
        return OrderDetailResource::collection($this->service->getOrders(userId: $user?->id, filter: $filter));
    }

    /**
     * Display the specified resource.
     *
     * @param OrderDetail $order
     * @return OrderDetailSingleResource
     */
    public function show(OrderDetail $order): OrderDetailSingleResource
    {
        Gate::authorize('view', $order);
        return new OrderDetailSingleResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderDetailRequest $request
     * @param OrderDetail $order
     * @return OrderDetailResource|JsonResponse
     */
    public function update(UpdateOrderDetailRequest $request, OrderDetail $order): OrderDetailResource|JsonResponse
    {
        Gate::authorize('update', $order);

        $validated = $request->validated();
        $model = $this->service->updateByCode($order->code, $validated);

        if (!is_null($model)) {
            return new OrderDetailResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش جزئیات سفارش',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param OrderDetail $order
     * @return JsonResponse
     */
    public function destroy(Request $request, OrderDetail $order): JsonResponse
    {
        Gate::authorize('delete', $order);

        $permanent = $request->user()->id === $order->creator?->id;
        $res = $this->service->deleteById($order->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return OrderResource|JsonResponse
     */
    public function updatePayment(UpdateOrderRequest $request, Order $order): JsonResponse|OrderResource
    {
        Gate::authorize('update', $order);

        $validated = $request->validated();
        $model = $this->service->updatePayment($order->id, $validated);

        if (!is_null($model)) {
            return new OrderResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش سفارش',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @return JsonResponse
     */
    public function paymentStatuses(): JsonResponse
    {
        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getPaymentStatuses(),
        ], ResponseCodes::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function sendStatuses(): JsonResponse
    {
        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getSendStatuses(),
        ], ResponseCodes::HTTP_OK);
    }
}
