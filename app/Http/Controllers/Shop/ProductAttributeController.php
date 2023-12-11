<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductAttributeRequest;
use App\Http\Requests\UpdateProductAttributeRequest;
use App\Http\Resources\ProductAttributeResource;
use App\Models\ProductAttribute;
use App\Models\User;
use App\Services\Contracts\ProductAttributeServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductAttributeController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param ProductAttributeServiceInterface $service
     */
    public function __construct(
        protected ProductAttributeServiceInterface $service
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
        return ProductAttributeResource::collection($this->service->getAttributes($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductAttributeRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreProductAttributeRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد ویژگی با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد ویژگی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ProductAttribute $productAttribute
     * @return ProductAttributeResource
     * @throws AuthorizationException
     */
    public function show(ProductAttribute $productAttribute): ProductAttributeResource
    {
        $this->authorize('view', $productAttribute);
        return new ProductAttributeResource($productAttribute);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductAttributeRequest $request
     * @param ProductAttribute $productAttribute
     * @return ProductAttributeResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(
        UpdateProductAttributeRequest $request,
        ProductAttribute              $productAttribute
    ): ProductAttributeResource|JsonResponse
    {
        $this->authorize('update', $productAttribute);

        $validated = $request->validated();
        $model = $this->service->updateById($productAttribute->id, $validated);

        if (!is_null($model)) {
            return new ProductAttributeResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش ویژگی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ProductAttribute $productAttribute
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, ProductAttribute $productAttribute): JsonResponse
    {
        $this->authorize('delete', $productAttribute);

        $permanent = $request->user()->id === $productAttribute->creator()?->id;
        $res = $this->service->deleteById($productAttribute->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
