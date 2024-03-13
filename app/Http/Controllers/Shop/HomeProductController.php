<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Http\Resources\Home\BrandResource;
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
     * This will use 'log_visit' to log too
     *
     * @param Product $product
     * @return HomeProductSingleResource
     */
    public function show(Product $product): HomeProductSingleResource
    {
        return new HomeProductSingleResource($product);
    }

    /**
     * @param Product $product
     * @return HomeProductResource
     */
    public function minifiedShow(Product $product): HomeProductResource
    {
        return new HomeProductResource($product);
    }

    /**
     * @param HomeProductSideFilter $filter
     * @return AnonymousResourceCollection
     */
    public function brandsFilter(HomeProductSideFilter $filter): AnonymousResourceCollection
    {
        return BrandResource::collection($this->service->getFilterBrands($filter));
    }

    /**
     * @param HomeProductSideFilter $filter
     * @return AnonymousResourceCollection
     */
    public function colorsAndSizesFilter(HomeProductSideFilter $filter): AnonymousResourceCollection
    {
        return ColorAndSizeResource::collection($this->service->getFilterColorsAndSizes($filter));
    }

    /**
     * @param HomeProductSideFilter $filter
     * @return JsonResponse
     */
    public function priceRangeFilter(HomeProductSideFilter $filter): JsonResponse
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
    public function dynamicFilters(HomeProductSideFilter $filter): AnonymousResourceCollection
    {
        return DynamicFilterResource::collection($this->service->getDynamicFilters($filter));
    }
}
