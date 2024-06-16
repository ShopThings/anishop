<?php

namespace App\Http\Controllers\Order;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderBadgeRequest;
use App\Http\Requests\UpdateOrderBadgeRequest;
use App\Http\Resources\OrderBadgeResource;
use App\Models\OrderBadge;
use App\Services\Contracts\OrderBadgeServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class OrderBadgeController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param OrderBadgeServiceInterface $service
     */
    public function __construct(
        protected OrderBadgeServiceInterface $service
    )
    {
        $this->considerDeletable = true;
        $this->policyModel = OrderBadge::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', OrderBadge::class);
        return OrderBadgeResource::collection($this->service->getBadges($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderBadgeRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderBadgeRequest $request): JsonResponse
    {
        Gate::authorize('create', OrderBadge::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد برچسب با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد برچسب',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param OrderBadge $orderBadge
     * @return OrderBadgeResource
     */
    public function show(OrderBadge $orderBadge): OrderBadgeResource
    {
        Gate::authorize('view', $orderBadge);
        return new OrderBadgeResource($orderBadge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderBadgeRequest $request
     * @param OrderBadge $orderBadge
     * @return OrderBadgeResource|JsonResponse
     */
    public function update(UpdateOrderBadgeRequest $request, OrderBadge $orderBadge): OrderBadgeResource|JsonResponse
    {
        Gate::authorize('update', $orderBadge);

        $validated = filter_validated_data($request->validated(), [
            'title',
            'color_hex',
            'should_return_order_product',
            'is_end_badge',
            'is_published',
        ]);

        if (!$orderBadge->is_title_editable) {
            unset($validated['title']);
        }

        $model = $this->service->updateById($orderBadge->id, $validated);

        if (!is_null($model)) {
            return new OrderBadgeResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش برچسب',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param OrderBadge $orderBadge
     * @return JsonResponse
     */
    public function destroy(Request $request, OrderBadge $orderBadge): JsonResponse
    {
        Gate::authorize('delete', $orderBadge);

        $permanent = $request->user()->id === $orderBadge->creator?->id;
        $res = $this->service->deleteById($orderBadge->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
