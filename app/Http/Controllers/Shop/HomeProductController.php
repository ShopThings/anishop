<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Http\Resources\Home\ColorAndSizeResource;
use App\Http\Resources\Home\DynamicFilterResource;
use App\Http\Resources\Home\ProductResource as HomeProductResource;
use App\Http\Resources\Home\ProductSingleResource as HomeProductSingleResource;
use App\Models\Product;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class HomeProductController extends Controller
{
    /**
     * @param ProductServiceInterface $service
     */
    public function __construct(
        protected ProductServiceInterface $service
    )
    {
    }

    /**
     * @param HomeProductFilter $filter
     * @return AnonymousResourceCollection
     */
    public function index(HomeProductFilter $filter): AnonymousResourceCollection
    {
        return HomeProductResource::collection($this->service->getFilteredProducts($filter));
    }

    /**
     * @param Product $product
     * @return AnonymousResourceCollection
     */
    public function show(Product $product): AnonymousResourceCollection
    {
        return HomeProductSingleResource::collection($this->service->getById($product->id));
    }

    /**
     * @param HomeProductSideFilter $filter
     * @return AnonymousResourceCollection
     */
    public function colorsAndSizes(HomeProductSideFilter $filter): AnonymousResourceCollection
    {
        return ColorAndSizeResource::collection($this->service->getFilterColorsAndSizes($filter));
    }

    /**
     * @param HomeProductSideFilter $filter
     * @return JsonResponse
     */
    public function priceRange(HomeProductSideFilter $filter): JsonResponse
    {
        $priceRange = $this->service->getFilterPriceRange($filter);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $priceRange,
        ], ResponseCodes::HTTP_OK);
    }

    /**
     * @param HomeProductSideFilter $filter
     * @return AnonymousResourceCollection
     */
    public function getDynamicFilters(HomeProductSideFilter $filter): AnonymousResourceCollection
    {
        return DynamicFilterResource::collection($this->service->getDynamicFilters($filter));
    }
}
