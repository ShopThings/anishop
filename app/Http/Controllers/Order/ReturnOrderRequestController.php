<?php

namespace App\Http\Controllers\Order;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReturnOrderItemRequest;
use App\Http\Requests\UpdateReturnOrderRequest;
use App\Http\Resources\ReturnOrderResource;
use App\Models\ReturnOrderRequest;
use App\Models\ReturnOrderRequestItem;
use App\Models\User;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ReturnOrderRequestController extends Controller
{
    use ControllerPaginateTrait;

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
     * @param Request $request
     * @param User|null $user
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request, ?User $user = null)
    {
        $this->authorize('viewAny', User::class);

        $params = $this->getPaginateParameters($request);

        return ReturnOrderResource::collection($this->service->getOrders(
            userId: $user?->id,
            searchText: $params['text'],
            limit: $params['limit'],
            page: $params['page'],
            order: $params['order']
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param ReturnOrderRequest $returnOrderRequest
     * @return ReturnOrderResource
     * @throws AuthorizationException
     */
    public function show(ReturnOrderRequest $returnOrderRequest)
    {
        $this->authorize('view', $returnOrderRequest);
        return new ReturnOrderResource($returnOrderRequest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateReturnOrderRequest $request
     * @param ReturnOrderRequest $returnOrderRequest
     * @return ReturnOrderResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateReturnOrderRequest $request, ReturnOrderRequest $returnOrderRequest)
    {
        $this->authorize('update', $returnOrderRequest);

        $validated = $request->validated([
            'not_accepted_description',
            'status',
            'seen_status',
        ]);
        $model = $this->service->updateById($returnOrderRequest->id, $validated);

        if (!is_null($model)) {
            return new ReturnOrderResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش سفارش مرجوعی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ReturnOrderRequest $returnOrderRequest)
    {
        $this->authorize('delete', $returnOrderRequest);

        $permanent = $request->user()->id === $returnOrderRequest->user()?->id;
        $res = $this->service->deleteById($returnOrderRequest->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param UpdateReturnOrderItemRequest $request
     * @param ReturnOrderRequest $returnOrderRequest
     * @param ReturnOrderRequestItem $returnOrderRequestItem
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function modifyItem(
        UpdateReturnOrderItemRequest $request,
        ReturnOrderRequest           $returnOrderRequest,
        ReturnOrderRequestItem       $returnOrderRequestItem
    )
    {
        $this->authorize('update', $returnOrderRequest);

        $validated = $request->validated('is_accepted');
        $model = $this->service->modifyItem($returnOrderRequestItem->id, $validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'محصول مرجوع شده، تایید شد.',
            ], ResponseCodes::HTTP_OK);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش محصول مرجوعی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
