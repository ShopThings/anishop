<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductAttributeValueRequest;
use App\Http\Requests\UpdateProductAttributeValueRequest;
use App\Http\Resources\ProductAttributeValueResource;
use App\Models\ProductAttributeValue;
use App\Services\Contracts\ProductAttributeValueServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductAttributeValueController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param ProductAttributeValueServiceInterface $service
     */
    public function __construct(
        protected ProductAttributeValueServiceInterface $service
    )
    {
        $this->policyModel = ProductAttributeValue::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', ProductAttributeValue::class);
        return ProductAttributeValueResource::collection($this->service->getValues($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductAttributeValueRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductAttributeValueRequest $request): JsonResponse
    {
        Gate::authorize('create', ProductAttributeValue::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد مقدار ویژگی با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد مقدار ویژگی',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param ProductAttributeValue $productAttributeValue
     * @return ProductAttributeValueResource
     */
    public function show(ProductAttributeValue $productAttributeValue): ProductAttributeValueResource
    {
        Gate::authorize('view', $productAttributeValue);
        return new ProductAttributeValueResource($productAttributeValue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductAttributeValueRequest $request
     * @param ProductAttributeValue $productAttributeValue
     * @return ProductAttributeValueResource|JsonResponse
     */
    public function update(
        UpdateProductAttributeValueRequest $request,
        ProductAttributeValue $productAttributeValue
    ): ProductAttributeValueResource|JsonResponse
    {
        Gate::authorize('update', $productAttributeValue);

        $validated = $request->validated();
        $model = $this->service->updateById($productAttributeValue->id, $validated);

        if (!is_null($model)) {
            return new ProductAttributeValueResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش مقدار ویژگی',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ProductAttributeValue $productAttributeValue
     * @return JsonResponse
     */
    public function destroy(Request $request, ProductAttributeValue $productAttributeValue): JsonResponse
    {
        Gate::authorize('delete', $productAttributeValue);

        $permanent = $request->user()->id === $productAttributeValue->creator?->id;
        $res = $this->service->deleteById($productAttributeValue->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
