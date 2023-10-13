<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductAttributeCategoryRequest;
use App\Http\Requests\UpdateProductAttributeCategoryRequest;
use App\Http\Resources\ProductAttributeCategoryResource;
use App\Models\ProductAttributeCategory;
use App\Models\User;
use App\Services\Contracts\ProductAttributeCategoryServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductAttributeCategoryController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

    /**
     * @param ProductAttributeCategoryServiceInterface $service
     */
    public function __construct(
        protected ProductAttributeCategoryServiceInterface $service
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

        return ProductAttributeCategoryResource::collection($this->service->getAttributeCategories(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductAttributeCategoryRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreProductAttributeCategoryRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد ویژگی دسته‌بندی با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد ویژگی دسته‌بندی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ProductAttributeCategory $productAttributeCategory
     * @return ProductAttributeCategoryResource
     * @throws AuthorizationException
     */
    public function show(ProductAttributeCategory $productAttributeCategory)
    {
        $this->authorize('view', $productAttributeCategory);
        return new ProductAttributeCategoryResource($productAttributeCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductAttributeCategoryRequest $request
     * @param ProductAttributeCategory $productAttributeCategory
     * @return ColorResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateProductAttributeCategoryRequest $request, ProductAttributeCategory $productAttributeCategory)
    {
        $this->authorize('update', $productAttributeCategory);

        $validated = $request->validated();
        $model = $this->service->updateById($productAttributeCategory->id, $validated);

        if (!is_null($model)) {
            return new ProductAttributeCategoryResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش ویژگی دسته‌بندی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ProductAttributeCategory $productAttributeCategory
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, ProductAttributeCategory $productAttributeCategory)
    {
        $this->authorize('delete', $productAttributeCategory);

        $permanent = $request->user()->id === $productAttributeCategory->creator()?->id;
        $res = $this->service->deleteById($productAttributeCategory->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
