<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWeightPostPriceRequest;
use App\Http\Requests\UpdateWeightPostPriceRequest;
use App\Http\Resources\WeightPostPriceResource;
use App\Models\WeightPostPrice;
use App\Services\Contracts\WeightPostPriceServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
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
        $this->policyModel = WeightPostPrice::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter)
    {
        Gate::authorize('viewAny', WeightPostPrice::class);
        return WeightPostPriceResource::collection($this->service->getPostPrices($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWeightPostPriceRequest $request
     * @return JsonResponse
     */
    public function store(StoreWeightPostPriceRequest $request): JsonResponse
    {
        Gate::authorize('create', WeightPostPrice::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'هزینه ارسال با موفقیت ثبت شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ثبت هزینه ارسال',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param WeightPostPrice $weightPostPrice
     * @return WeightPostPriceResource
     */
    public function show(WeightPostPrice $weightPostPrice): WeightPostPriceResource
    {
        Gate::authorize('view', $weightPostPrice);
        return new WeightPostPriceResource($weightPostPrice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWeightPostPriceRequest $request
     * @param WeightPostPrice $weightPostPrice
     * @return WeightPostPriceResource|JsonResponse
     */
    public function update(UpdateWeightPostPriceRequest $request, WeightPostPrice $weightPostPrice): JsonResponse|WeightPostPriceResource
    {
        Gate::authorize('update', $weightPostPrice);

        $validated = $request->validated();
        $model = $this->service->updateById($weightPostPrice->id, $validated);

        if (!is_null($model)) {
            return new WeightPostPriceResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش هزینه ارسال',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param WeightPostPrice $weightPostPrice
     * @return JsonResponse
     */
    public function destroy(Request $request, WeightPostPrice $weightPostPrice): JsonResponse
    {
        Gate::authorize('delete', $weightPostPrice);

        $permanent = $request->user()->id === $weightPostPrice->creator?->id;
        $res = $this->service->deleteById($weightPostPrice->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
