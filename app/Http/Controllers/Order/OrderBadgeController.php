<?php

namespace App\Http\Controllers\Order;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderBadgeRequest;
use App\Http\Requests\UpdateOrderBadgeRequest;
use App\Http\Resources\OrderBadgeResource;
use App\Models\OrderBadge;
use App\Models\User;
use App\Services\Contracts\OrderBadgeServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return OrderBadgeResource::collection($this->service->getBadges($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderBadgeRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreOrderBadgeRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد برچسب با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد برچسب',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param OrderBadge $orderBadge
     * @return OrderBadgeResource
     * @throws AuthorizationException
     */
    public function show(OrderBadge $orderBadge): OrderBadgeResource
    {
        $this->authorize('view', $orderBadge);
        return new OrderBadgeResource($orderBadge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderBadgeRequest $request
     * @param OrderBadge $orderBadge
     * @return OrderBadgeResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateOrderBadgeRequest $request, OrderBadge $orderBadge): OrderBadgeResource|JsonResponse
    {
        $this->authorize('update', $orderBadge);

        $validated = $request->validated([
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
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش برچسب',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param OrderBadge $orderBadge
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, OrderBadge $orderBadge): JsonResponse
    {
        $this->authorize('delete', $orderBadge);

        $permanent = $request->user()->id === $orderBadge->creator?->id;
        $res = $this->service->deleteById($orderBadge->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
