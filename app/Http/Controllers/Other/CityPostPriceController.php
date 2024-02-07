<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityPostPriceRequest;
use App\Http\Requests\UpdateCityPostPriceRequest;
use App\Http\Resources\CityPostPriceResource;
use App\Models\CityPostPrice;
use App\Models\User;
use App\Services\Contracts\CityPostPriceServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        return CityPostPriceResource::collection($this->service->getPostPrices($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCityPostPriceRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreCityPostPriceRequest $request): JsonResponse
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
     * @param CityPostPrice $cityPostPrice
     * @return CityPostPriceResource
     * @throws AuthorizationException
     */
    public function show(CityPostPrice $cityPostPrice): CityPostPriceResource
    {
        $this->authorize('view', $cityPostPrice);
        return new CityPostPriceResource($cityPostPrice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCityPostPriceRequest $request
     * @param CityPostPrice $cityPostPrice
     * @return CityPostPriceResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(
        UpdateCityPostPriceRequest $request,
        CityPostPrice              $cityPostPrice
    ): CityPostPriceResource|JsonResponse
    {
        $this->authorize('update', $cityPostPrice);

        $validated = $request->validated();
        $model = $this->service->updateById($cityPostPrice->id, $validated);

        if (!is_null($model)) {
            return new CityPostPriceResource($model);
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
     * @param CityPostPrice $cityPostPrice
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, CityPostPrice $cityPostPrice): JsonResponse
    {
        $this->authorize('delete', $cityPostPrice);

        $permanent = $request->user()->id === $cityPostPrice->creator?->id;
        $res = $this->service->deleteById($cityPostPrice->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
