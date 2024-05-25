<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\HomeCategoryFilter;
use App\Http\Resources\Home\MainCategoryResource;
use App\Services\Contracts\CategoryServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeCategoryController extends Controller
{
    /**
     * @param CategoryServiceInterface $service
     */
    public function __construct(
        protected CategoryServiceInterface $service
    )
    {
    }

    /**
     * @param HomeCategoryFilter $filter
     * @return AnonymousResourceCollection
     */
    public function categories(HomeCategoryFilter $filter): AnonymousResourceCollection
    {
        return MainCategoryResource::collection($this->service->getPublishedCategories($filter, true));
    }
}
