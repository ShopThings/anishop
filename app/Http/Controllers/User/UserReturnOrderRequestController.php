<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserReturnOrderRequest;
use App\Http\Resources\User\UserReturnableOrderResource;
use App\Http\Resources\User\UserReturnOrderResource;
use App\Http\Resources\User\UserReturnOrderSingleResource;
use App\Models\OrderDetail;
use App\Models\ReturnOrderRequest;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserReturnOrderRequestController extends Controller
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
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function latest(Request $request): AnonymousResourceCollection
    {
        return UserReturnOrderResource::collection($this->service->getLatestUserRequests(
            userId: $request->user()->id,
            limit: 5
        ));
    }

    /**
     * Display a listing of possible candidate orders that can return.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function returnableOrders(Request $request): AnonymousResourceCollection
    {
        return UserReturnableOrderResource::collection($this->service->getReturnableOrders(
            $request->user()->id
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
        return UserReturnOrderResource::collection($this->service->getRequests(
            userId: $request->user()->id,
            filter: $filter
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param OrderDetail $order
     * @return JsonResponse
     */
    public function store(Request $request, OrderDetail $order): JsonResponse
    {
        if (!$this->service->canReturnOrder($order)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'این سفارش قابل مرجوع نمودن نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        $model = $this->service->createUserRequest($request->user()->id, $order->id);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'درخواست مرجوع سفارش ثبت شد.',
            ], ResponseCodes::HTTP_OK);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ثبت درخواست مرجوع سفارش! لطفا دوباره تلاش نمایید.',
        ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Display the specified resource.
     *
     * @param ReturnOrderRequest $returnOrder
     * @return UserReturnOrderSingleResource
     */
    public function show(ReturnOrderRequest $returnOrder): UserReturnOrderSingleResource
    {
        return new UserReturnOrderSingleResource($returnOrder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserReturnOrderRequest $request
     * @param ReturnOrderRequest $returnOrder
     * @return UserReturnOrderSingleResource|JsonResponse
     */
    public function update(
        UpdateUserReturnOrderRequest $request,
        ReturnOrderRequest $returnOrder
    ): UserReturnOrderSingleResource|JsonResponse
    {
        $validated = $request->validated(['description', 'items']);

        $model = $this->service->updateUserRequestByModel(
            userId: $request->user()->id,
            model: $returnOrder,
            attributes: [
                'description' => $validated['description'],
                'items' => $validated['items'],
            ]
        );

        if (false === $model) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'انتخاب حداقل یک مورد برای مرجوع الزامی می‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        } elseif (!is_null($model)) {
            return new UserReturnOrderSingleResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ثبت اطلاعات مرجوع',
        ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
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
        if (!$this->service->canCancelOrder($returnOrder)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'امکان لغو درخواست مرجوع وجود ندارد.',
            ]);
        }

        $res = $this->service->cancelUserRequestById(
            userId: $request->user()->id,
            requestId: $returnOrder->id,
            permanent: true
        );

        if ($res) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'درخواست مرجوع لغو شد.',
            ], ResponseCodes::HTTP_OK);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در لغو درخواست! لطفا دوباره تلاش نمایید.',
        ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
