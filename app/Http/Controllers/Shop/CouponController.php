<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Models\User;
use App\Services\Contracts\CouponServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        return CouponResource::collection($this->service->getCoupons($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCouponRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreCouponRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد کوپن با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد کوپن',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Coupon $coupon
     * @return CouponResource
     * @throws AuthorizationException
     */
    public function show(Coupon $coupon): CouponResource
    {
        $this->authorize('view', $coupon);
        return new CouponResource($coupon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCouponRequest $request
     * @param Coupon $coupon
     * @return CouponResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): CouponResource|JsonResponse
    {
        $this->authorize('update', $coupon);

        $validated = $request->validated();
        $model = $this->service->updateById($coupon->id, $validated);

        if (!is_null($model)) {
            return new CouponResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش کوپن',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Coupon $coupon
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Coupon $coupon): JsonResponse
    {
        $this->authorize('delete', $coupon);

        $permanent = $request->user()->id === $coupon->creator()?->id;
        $res = $this->service->deleteById($coupon->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
