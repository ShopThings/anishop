<?php

namespace App\Http\Controllers\Shop;

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
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

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
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $params = $this->getPaginateParameters($request);

        return ProductResource::collection($this->service->getProducts(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreProductRequest $request)
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
    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return new ProductSingleResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
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
    public function destroy(Request $request, Product $product)
    {
        $this->authorize('delete', $product);

        $permanent = $request->user()->id === $product->creator()?->id;
        $res = $this->service->deleteById($product->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
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
    public function modifyProducts(StoreProductPropertyRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validated();

        return ProductPropertyResource::collection($this->service->modifyProducts($product->id, $validated['products']));
    }

    public function batchUpdateInfo(UpdateMultiProductInfo $request)
    {
        $this->authorize('batchUpdate', User::class);

        //
    }

    public function batchUpdatePrice(UpdateMultiProductPrice $request)
    {
        $this->authorize('batchUpdate', User::class);

        //
    }
}
