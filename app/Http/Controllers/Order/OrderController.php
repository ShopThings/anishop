<?php

namespace App\Http\Controllers\Order;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderDetailRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Services\Contracts\OrderServiceInterface;
use App\Support\Filter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @param User|null $user
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter, ?User $user = null): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return OrderDetailResource::collection($this->service->getOrders(userId: $user?->id, filter: $filter));
    }

    /**
     * Display the specified resource.
     *
     * @param OrderDetail $order
     * @return OrderDetailResource
     * @throws AuthorizationException
     */
    public function show(OrderDetail $order): OrderDetailResource
    {
        $this->authorize('view', $order);
        return new OrderDetailResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderDetailRequest $request
     * @param OrderDetail $order
     * @return OrderDetailResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateOrderDetailRequest $request, OrderDetail $order): OrderDetailResource|JsonResponse
    {
        $this->authorize('update', $order);

        $validated = $request->validated();
        $model = $this->service->updateById($order->id, $validated);

        if (!is_null($model)) {
            return new OrderDetailResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش جزئیات سفارش',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param OrderDetail $order
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, OrderDetail $order): JsonResponse
    {
        $this->authorize('delete', $order);

        $permanent = $request->user()->id === $order->creator()?->id;
        $res = $this->service->deleteById($order->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return OrderResource|JsonResponse
     * @throws AuthorizationException
     */
    public function updatePayment(UpdateOrderRequest $request, Order $order): JsonResponse|OrderResource
    {
        $this->authorize('update', $order);

        $validated = $request->validated();
        $model = $this->service->updatePayment($order->id, $validated);

        if (!is_null($model)) {
            return new OrderResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش سفارش',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentStatuses(): JsonResponse
    {
        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getPaymentStatuses(),
        ], ResponseCodes::HTTP_OK);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendStatuses(): JsonResponse
    {
        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getSendStatuses(),
        ], ResponseCodes::HTTP_OK);
    }
}
