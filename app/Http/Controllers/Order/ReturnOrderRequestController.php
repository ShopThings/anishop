<?php

namespace App\Http\Controllers\Order;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReturnOrderItemRequest;
use App\Http\Requests\UpdateReturnOrderRequest;
use App\Http\Resources\ReturnOrderResource;
use App\Http\Resources\ReturnOrderSingleResource;
use App\Models\ReturnOrderRequest;
use App\Models\ReturnOrderRequestItem;
use App\Models\User;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ReturnOrderRequestController extends Controller
{
    /**
     * @param ReturnOrderServiceInterface $service
     */
    public function __construct(
        protected ReturnOrderServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @param User|null $user
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter, ?User $user = null): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', ReturnOrderRequest::class);
        return ReturnOrderResource::collection($this->service->getRequests(userId: $user?->id, filter: $filter));
    }

    /**
     * Display the specified resource.
     *
     * @param ReturnOrderRequest $returnOrder
     * @return ReturnOrderSingleResource
     */
    public function show(ReturnOrderRequest $returnOrder): ReturnOrderSingleResource
    {
        Gate::authorize('view', $returnOrder);
        return new ReturnOrderSingleResource($returnOrder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateReturnOrderRequest $request
     * @param ReturnOrderRequest $returnOrder
     * @return ReturnOrderSingleResource|JsonResponse
     */
    public function update(
        UpdateReturnOrderRequest $request,
        ReturnOrderRequest $returnOrder
    ): ReturnOrderSingleResource|JsonResponse
    {
        Gate::authorize('update', $returnOrder);

        $validated = $request->validated([
            'not_accepted_description',
            'status',
            'seen_status',
        ]);
        $model = $this->service->updateByCode($returnOrder->code, $validated);

        if (!is_null($model)) {
            return new ReturnOrderSingleResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش سفارش مرجوعی',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ReturnOrderRequest $returnOrder
     * @return JsonResponse
     */
    public function destroy(Request $request, ReturnOrderRequest $returnOrder): JsonResponse
    {
        Gate::authorize('delete', $returnOrder);

        $permanent = $request->user()->id === $returnOrder->user()?->id;
        $res = $this->service->deleteById($returnOrder->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param UpdateReturnOrderItemRequest $request
     * @param ReturnOrderRequest $returnOrder
     * @param ReturnOrderRequestItem $returnOrderItem
     * @return JsonResponse
     */
    public function modifyItem(
        UpdateReturnOrderItemRequest $request,
        ReturnOrderRequest     $returnOrder,
        ReturnOrderRequestItem $returnOrderItem
    ): JsonResponse
    {
        Gate::authorize('update', $returnOrder);

        $validated = $request->validated('is_accepted');
        $model = $this->service->modifyItem($returnOrderItem->id, $validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'محصول مرجوع شده، تایید شد.',
            ], ResponseCodes::HTTP_OK);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش محصول مرجوعی',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function statuses(): JsonResponse
    {
        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getStatuses(),
        ], ResponseCodes::HTTP_OK);
    }
}
