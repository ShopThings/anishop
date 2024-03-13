<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\AmazingOfferSliderResource;
use App\Http\Resources\Home\MainAllSlidersResource;
use App\Http\Resources\Home\MainCategorySliderResource;
use App\Http\Resources\Home\MainSliderResource;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\Contracts\SliderServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeSliderController extends Controller
{
    /**
     * @param SliderServiceInterface $service
     */
    public function __construct(
        protected SliderServiceInterface $service
    )
    {
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function main(): AnonymousResourceCollection
    {
        return MainSliderResource::collection($this->service->getMainSlider());
    }

    /**
     * @param CategoryServiceInterface $service
     * @return AnonymousResourceCollection
     */
    public function sliderCategories(CategoryServiceInterface $service): AnonymousResourceCollection
    {
        return MainCategorySliderResource::collection($service->getSliderCategories());
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function amazingOffers(): AnonymousResourceCollection
    {
        return AmazingOfferSliderResource::collection($this->service->getAmazingOfferSlider());
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function allSliders(): AnonymousResourceCollection
    {
        return MainAllSlidersResource::collection($this->service->getAllMainSliders());
    }
}
