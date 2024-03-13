<?php

namespace App\Http\Controllers\Payment;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\Contracts\PaymentMethodServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class PaymentMethodController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param PaymentMethodServiceInterface $service
     */
    public function __construct(
        protected readonly PaymentMethodServiceInterface $service
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
        return PaymentMethodResource::collection($this->service->getMethods($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePaymentMethodRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StorePaymentMethodRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد روش پرداخت با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد روش پرداخت',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param PaymentMethod $paymentMethod
     * @return PaymentMethodResource
     * @throws AuthorizationException
     */
    public function show(PaymentMethod $paymentMethod): PaymentMethodResource
    {
        $this->authorize('view', $paymentMethod);
        return new PaymentMethodResource($paymentMethod);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePaymentMethodRequest $request
     * @param PaymentMethod $paymentMethod
     * @return PaymentMethodResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(
        UpdatePaymentMethodRequest $request,
        PaymentMethod $paymentMethod
    ): PaymentMethodResource|JsonResponse
    {
        $this->authorize('update', $paymentMethod);

        $validated = $request->validated();
        unset($validated['code']);
        unset($validated['is_deletable']);
        $model = $this->service->updateById($paymentMethod->id, $validated);

        if (!is_null($model)) {
            return new PaymentMethodResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش روش پرداخت',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param PaymentMethod $paymentMethod
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, PaymentMethod $paymentMethod): JsonResponse
    {
        $this->authorize('delete', $paymentMethod);

        $permanent = $request->user()->id === $paymentMethod->creator?->id;
        $res = $this->service->deleteById($paymentMethod->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
