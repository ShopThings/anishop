<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\ProductFilter;
use App\Http\Requests\StoreProductGalleryRequest;
use App\Http\Requests\StoreProductPropertyRequest;
use App\Http\Requests\StoreProductRelatedProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateMultiProductInfo;
use App\Http\Requests\UpdateMultiProductPrice;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductPropertyResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductSingleResource;
use App\Http\Resources\Showing\ImageShowInfoResource;
use App\Models\Product;
use App\Services\Contracts\ProductServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param ProductServiceInterface $service
     */
    public function __construct(
        protected ProductServiceInterface $service
    )
    {
        $this->policyModel = Product::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProductFilter $filter
     * @return AnonymousResourceCollection
     */
    public function index(ProductFilter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Product::class);
        return ProductResource::collection($this->service->getProducts($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        Gate::authorize('create', Product::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد محصول با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد محصول',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return ProductSingleResource
     */
    public function show(Product $product): ProductSingleResource
    {
        Gate::authorize('view', $product);
        return new ProductSingleResource($product);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return JsonResponse|ProductResource
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse|ProductResource
    {
        Gate::authorize('update', $product);

        $validated = $request->validated();
        $model = $this->service->updateById($product->id, $validated);

        if (!is_null($model)) {
            return new ProductResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش محصول',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        Gate::authorize('delete', $product);

        $permanent = $request->user()->id === $product->creator?->id;
        $res = $this->service->deleteById($product->id, $permanent);

        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Product $product
     * @return AnonymousResourceCollection
     */
    public function showVariants(Product $product): AnonymousResourceCollection
    {
        Gate::authorize('view', $product);
        return ProductPropertyResource::collection($product->items);
    }

    /**
     * @param StoreProductGalleryRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function storeGalleryImages(
        StoreProductGalleryRequest $request,
        Product                    $product
    ): JsonResponse
    {
        Gate::authorize('update', $product);

        $validated = $request->validated();
        $res = $this->service->createGalley($product->id, $validated['images']);

        if ($res) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد گالری تصاویر با موفقیت انجام شد.',
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ذخیره تصاویر',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Product $product
     * @return AnonymousResourceCollection
     */
    public function showGalleryImages(Product $product): AnonymousResourceCollection
    {
        Gate::authorize('view', $product);

        $images = $product->images()->with('image')->get()->pluck('image');
        return ImageShowInfoResource::collection($images);
    }

    /**
     * @param StoreProductRelatedProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function storeRelatedProducts(
        StoreProductRelatedProductRequest $request,
        Product                           $product
    ): JsonResponse
    {
        Gate::authorize('update', $product);

        $validated = $request->validated();
        $res = $this->service->createRelatedProducts($product->id, $validated['products']);

        if ($res) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'محصولات مرتبط با موفقیت ثبت شد.',
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ذخیره محصولات مرتبط',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Product $product
     * @return AnonymousResourceCollection
     */
    public function showRelatedProducts(Product $product): AnonymousResourceCollection
    {
        Gate::authorize('view', $product);

        $products = $product->relatedProducts()
            ->with(['relatedProduct', 'relatedProduct.image'])
            ->get()
            ->pluck('relatedProduct');
        return ProductResource::collection($products);
    }

    /**
     * @param StoreProductPropertyRequest $request
     * @param Product $product
     * @return AnonymousResourceCollection
     */
    public function modifyProducts(
        StoreProductPropertyRequest $request,
        Product                     $product
    ): AnonymousResourceCollection
    {
        Gate::authorize('update', $product);

        $validated = $request->validated();

        return ProductPropertyResource::collection($this->service->modifyProducts(
            productId: $product->id,
            products: $validated['products']
        ));
    }

    /**
     * @param UpdateMultiProductInfo $request
     * @return JsonResponse
     */
    public function batchUpdateInfo(UpdateMultiProductInfo $request): JsonResponse
    {
        Gate::authorize('batchUpdate', Product::class);

        $validated = $request->validated();

        $res = $this->service->updateBatchInfo($validated['ids'], $validated);

        if ($res) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'عملیات با موفقیت انجام شد.',
            ], ResponseCodes::HTTP_OK);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در تغییر دسته‌ای مشخصات',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param UpdateMultiProductPrice $request
     * @return JsonResponse
     */
    public function batchUpdatePrice(UpdateMultiProductPrice $request): JsonResponse
    {
        Gate::authorize('batchUpdate', Product::class);

        $validated = $request->validated();

        $res = $this->service->updateBatchPrice(
            ids: $validated['ids'],
            percentage: $validated['price_percentage'],
            changeType: ChangeMultipleProductPriceTypesEnum::tryFrom($validated['change_type'])
        );

        if ($res) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'قیمت محصولات انتخاب شده ' . $validated['price_percentage'] . ' درصد'
                    . (
                    ChangeMultipleProductPriceTypesEnum::INCREASE->value === $validated['change_type']
                        ? 'افزایش'
                        : 'کاهش'
                    ) . ' یافت.'
            ], ResponseCodes::HTTP_OK);
        }

        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در تغییر دسته‌ای قیمت',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
