<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityPostPriceRequest;
use App\Http\Requests\UpdateCityPostPriceRequest;
use App\Http\Resources\CityPostPriceResource;
use App\Models\CityPostPrice;
use App\Services\Contracts\CityPostPriceServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class CityPostPriceController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param CityPostPriceServiceInterface $service
     */
    public function __construct(
        protected CityPostPriceServiceInterface $service
    )
    {
        $this->policyModel = CityPostPrice::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', CityPostPrice::class);
        return CityPostPriceResource::collection($this->service->getPostPrices($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCityPostPriceRequest $request
     * @return JsonResponse
     */
    public function store(StoreCityPostPriceRequest $request): JsonResponse
    {
        Gate::authorize('create', CityPostPrice::class);

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
     * @param CityPostPrice $cityPostPrice
     * @return CityPostPriceResource
     */
    public function show(CityPostPrice $cityPostPrice): CityPostPriceResource
    {
        Gate::authorize('view', $cityPostPrice);
        return new CityPostPriceResource($cityPostPrice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCityPostPriceRequest $request
     * @param CityPostPrice $cityPostPrice
     * @return CityPostPriceResource|JsonResponse
     */
    public function update(
        UpdateCityPostPriceRequest $request,
        CityPostPrice              $cityPostPrice
    ): CityPostPriceResource|JsonResponse
    {
        Gate::authorize('update', $cityPostPrice);

        $validated = $request->validated();
        $model = $this->service->updateById($cityPostPrice->id, $validated);

        if (!is_null($model)) {
            return new CityPostPriceResource($model);
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
     * @param CityPostPrice $cityPostPrice
     * @return JsonResponse
     */
    public function destroy(Request $request, CityPostPrice $cityPostPrice): JsonResponse
    {
        Gate::authorize('delete', $cityPostPrice);

        $permanent = $request->user()->id === $cityPostPrice->creator?->id;
        $res = $this->service->deleteById($cityPostPrice->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
