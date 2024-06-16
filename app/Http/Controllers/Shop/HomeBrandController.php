<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\BrandResource;
use App\Http\Resources\Home\MainBrandSliderResource;
use App\Services\Contracts\BrandServiceInterface;
use App\Support\Filter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeBrandController extends Controller
{
    /**
     * @param BrandServiceInterface $service
     */
    public function __construct(
        protected BrandServiceInterface $service
    )
    {
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function brandSlider(): AnonymousResourceCollection
    {
        return MainBrandSliderResource::collection($this->service->getSliderBrands());
    }

    /**
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function brands(Filter $filter): AnonymousResourceCollection
    {
        return BrandResource::collection($this->service->getPublishedBrands($filter));
    }
}
