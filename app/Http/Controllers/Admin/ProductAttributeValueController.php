<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductAttributeValueRequest;
use App\Http\Requests\UpdateProductAttributeValueRequest;
use App\Http\Resources\ProductAttributeValueResource;
use App\Models\ProductAttributeValue;
use App\Models\User;
use App\Services\Contracts\ProductAttributeValueServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductAttributeValueController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

    /**
     * @param ProductAttributeValueServiceInterface $service
     */
    public function __construct(
        protected ProductAttributeValueServiceInterface $service
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

        return ProductAttributeValueResource::collection($this->service->getValues(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductAttributeValueRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreProductAttributeValueRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد مقدار ویژگی با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد مقدار ویژگی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ProductAttributeValue $productAttributeValue
     * @return ProductAttributeValueResource
     * @throws AuthorizationException
     */
    public function show(ProductAttributeValue $productAttributeValue)
    {
        $this->authorize('view', $productAttributeValue);
        return new ProductAttributeValueResource($productAttributeValue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductAttributeValueRequest $request
     * @param ProductAttributeValue $productAttributeValue
     * @return ProductAttributeValueResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateProductAttributeValueRequest $request, ProductAttributeValue $productAttributeValue)
    {
        $this->authorize('update', $productAttributeValue);

        $validated = $request->validated();
        $model = $this->service->updateById($productAttributeValue->id, $validated);

        if (!is_null($model)) {
            return new ProductAttributeValueResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش مقدار ویژگی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ProductAttributeValue $productAttributeValue
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, ProductAttributeValue $productAttributeValue)
    {
        $this->authorize('delete', $productAttributeValue);

        $permanent = $request->user()->id === $productAttributeValue->creator()?->id;
        $res = $this->service->deleteById($productAttributeValue->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
