<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductAttributeCategoryRequest;
use App\Http\Requests\UpdateProductAttributeCategoryRequest;
use App\Http\Resources\ProductAttributeCategoryResource;
use App\Models\ProductAttributeCategory;
use App\Services\Contracts\ProductAttributeCategoryServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductAttributeCategoryController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param ProductAttributeCategoryServiceInterface $service
     */
    public function __construct(
        protected ProductAttributeCategoryServiceInterface $service
    )
    {
        $this->policyModel = ProductAttributeCategory::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', ProductAttributeCategory::class);
        return ProductAttributeCategoryResource::collection($this->service->getAttributeCategories($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductAttributeCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductAttributeCategoryRequest $request): JsonResponse
    {
        Gate::authorize('create', ProductAttributeCategory::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد ویژگی دسته‌بندی با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد ویژگی دسته‌بندی',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param ProductAttributeCategory $productAttributeCategory
     * @return ProductAttributeCategoryResource
     */
    public function show(ProductAttributeCategory $productAttributeCategory): ProductAttributeCategoryResource
    {
        Gate::authorize('view', $productAttributeCategory);
        return new ProductAttributeCategoryResource($productAttributeCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductAttributeCategoryRequest $request
     * @param ProductAttributeCategory $productAttributeCategory
     * @return ProductAttributeCategoryResource|JsonResponse
     */
    public function update(
        UpdateProductAttributeCategoryRequest $request,
        ProductAttributeCategory $productAttributeCategory
    ): ProductAttributeCategoryResource|JsonResponse
    {
        Gate::authorize('update', $productAttributeCategory);

        $validated = $request->validated();
        $model = $this->service->updateById($productAttributeCategory->id, $validated);

        if (!is_null($model)) {
            return new ProductAttributeCategoryResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش ویژگی دسته‌بندی',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ProductAttributeCategory $productAttributeCategory
     * @return JsonResponse
     */
    public function destroy(Request $request, ProductAttributeCategory $productAttributeCategory): JsonResponse
    {
        Gate::authorize('delete', $productAttributeCategory);

        $permanent = $request->user()->id === $productAttributeCategory->creator?->id;
        $res = $this->service->deleteById($productAttributeCategory->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
