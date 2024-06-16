<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Services\Contracts\CouponServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class CouponController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param CouponServiceInterface $service
     */
    public function __construct(
        protected CouponServiceInterface $service
    )
    {
        $this->policyModel = Coupon::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Coupon::class);
        return CouponResource::collection($this->service->getCoupons($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCouponRequest $request
     * @return JsonResponse
     */
    public function store(StoreCouponRequest $request): JsonResponse
    {
        Gate::authorize('create', Coupon::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد کوپن تخفیف با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد کوپن تخفیف',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Coupon $coupon
     * @return CouponResource
     */
    public function show(Coupon $coupon): CouponResource
    {
        Gate::authorize('view', $coupon);
        return new CouponResource($coupon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCouponRequest $request
     * @param Coupon $coupon
     * @return CouponResource|JsonResponse
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): CouponResource|JsonResponse
    {
        Gate::authorize('update', $coupon);

        $validated = $request->validated();

        unset($validated['code']);

        $model = $this->service->updateById($coupon->id, $validated);

        if (!is_null($model)) {
            return new CouponResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش کوپن تخفیف',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Coupon $coupon
     * @return JsonResponse
     */
    public function destroy(Request $request, Coupon $coupon): JsonResponse
    {
        Gate::authorize('delete', $coupon);

        $permanent = $request->user()->id === $coupon->creator?->id;
        $res = $this->service->deleteById($coupon->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
