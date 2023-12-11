<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWeightPostPriceRequest;
use App\Http\Requests\UpdateWeightPostPriceRequest;
use App\Http\Resources\WeightPostPriceResource;
use App\Models\User;
use App\Models\WeightPostPrice;
use App\Services\Contracts\WeightPostPriceServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class WeightPostPriceController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param WeightPostPriceServiceInterface $service
     */
    public function __construct(
        protected WeightPostPriceServiceInterface $service
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
    public function index(Filter $filter)
    {
        $this->authorize('viewAny', User::class);
        return WeightPostPriceResource::collection($this->service->getPostPrices($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWeightPostPriceRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreWeightPostPriceRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'هزینه ارسال با موفقیت ثبت شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت هزینه ارسال',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param WeightPostPrice $weightPostPrice
     * @return WeightPostPriceResource
     * @throws AuthorizationException
     */
    public function show(WeightPostPrice $weightPostPrice): WeightPostPriceResource
    {
        $this->authorize('view', $weightPostPrice);
        return new WeightPostPriceResource($weightPostPrice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWeightPostPriceRequest $request
     * @param WeightPostPrice $weightPostPrice
     * @return WeightPostPriceResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateWeightPostPriceRequest $request, WeightPostPrice $weightPostPrice): JsonResponse|WeightPostPriceResource
    {
        $this->authorize('update', $weightPostPrice);

        $validated = $request->validated();
        $model = $this->service->updateById($weightPostPrice->id, $validated);

        if (!is_null($model)) {
            return new WeightPostPriceResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش هزینه ارسال',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param WeightPostPrice $weightPostPrice
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, WeightPostPrice $weightPostPrice): JsonResponse
    {
        $this->authorize('delete', $weightPostPrice);

        $permanent = $request->user()->id === $weightPostPrice->creator()?->id;
        $res = $this->service->deleteById($weightPostPrice->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
