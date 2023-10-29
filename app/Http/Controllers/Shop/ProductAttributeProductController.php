<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductAttributeProductResource;
use App\Models\Product;
use App\Models\User;
use App\Services\Contracts\ProductAttributeProductServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductAttributeProductController extends Controller
{
    /**
     * @param ProductAttributeProductServiceInterface $service
     */
    public function __construct(
        protected ProductAttributeProductServiceInterface $service
    )
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Product $product)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create(array_merge($validated, ['product' => $product->id]));

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'تغییر مقدار ویژگی محصول با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در تغییر مقدار ویژگی محصول',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return ProductAttributeProductResource
     * @throws AuthorizationException
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return new ProductAttributeProductResource($this->service->getProductAttributes($product->id));
    }
}
