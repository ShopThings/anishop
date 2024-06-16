<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductAttributeProductRequest;
use App\Http\Resources\ProductAttributeProductResource;
use App\Http\Resources\Showing\ProductAttributeCategoryShowResource;
use App\Models\Product;
use App\Models\ProductAttributeProduct;
use App\Services\Contracts\ProductAttributeCategoryServiceInterface;
use App\Services\Contracts\ProductAttributeProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductAttributeProductController extends Controller
{
    /**
     * @param ProductAttributeProductServiceInterface $service
     * @param ProductAttributeCategoryServiceInterface $productAttributeCategoryService
     */
    public function __construct(
        protected ProductAttributeProductServiceInterface  $service,
        protected ProductAttributeCategoryServiceInterface $productAttributeCategoryService
    )
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductAttributeProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function store(StoreProductAttributeProductRequest $request, Product $product): JsonResponse
    {
        Gate::authorize('create', ProductAttributeProduct::class);

        $validated = $request->validated();
        $model = $this->service->modifyProductAttributes(
            product: $product,
            attributeValues: $validated['values']
        );

        if ($model) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'تغییر مقدار ویژگی محصول با موفقیت انجام شد.',
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در تغییر مقدار ویژگی محصول',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        Gate::authorize('view', $product);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => [
                'category_attributes' => ProductAttributeCategoryShowResource::collection(
                    $this->productAttributeCategoryService->getProductAttributeCategories($product->id)
                ),
                'product_attributes' => ProductAttributeProductResource::collection(
                    $this->service->getProductAttributes($product->id)
                ),
            ],
        ]);
    }
}
