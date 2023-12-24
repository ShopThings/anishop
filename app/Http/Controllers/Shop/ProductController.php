<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductPropertyRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateMultiProductInfo;
use App\Http\Requests\UpdateMultiProductPrice;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductPropertyResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductSingleResource;
use App\Models\Product;
use App\Models\User;
use App\Services\Contracts\ProductServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        return ProductResource::collection($this->service->getProducts($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد محصول با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد محصول',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return ProductSingleResource
     * @throws AuthorizationException
     */
    public function show(Product $product): ProductSingleResource
    {
        $this->authorize('view', $product);
        return new ProductSingleResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse|ProductResource
    {
        $this->authorize('update', $product);

        $validated = $request->validated();
        $model = $this->service->updateById($product->id, $validated);

        if (!is_null($model)) {
            return new ProductResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش محصول',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $permanent = $request->user()->id === $product->creator()?->id;
        $res = $this->service->deleteById($product->id, $permanent);
        if ($res) return response()->json([], ResponseCodes::HTTP_NO_CONTENT);

        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param StoreProductPropertyRequest $request
     * @param Product $product
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function modifyProducts(StoreProductPropertyRequest $request, Product $product): AnonymousResourceCollection
    {
        $this->authorize('update', $product);

        $validated = $request->validated();

        return ProductPropertyResource::collection($this->service->modifyProducts($product->id, $validated['products']));
    }

    public function batchUpdateInfo(UpdateMultiProductInfo $request)
    {
        $this->authorize('batchUpdate', User::class);

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
        ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param UpdateMultiProductPrice $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function batchUpdatePrice(UpdateMultiProductPrice $request): JsonResponse
    {
        $this->authorize('batchUpdate', User::class);

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
        ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
