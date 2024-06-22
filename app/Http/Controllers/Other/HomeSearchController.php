<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\HomeBlogFilter;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Resources\Home\SearchBlogResource;
use App\Http\Resources\Home\SearchProductResource;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Services\Contracts\BlogServiceInterface;
use App\Services\Contracts\BrandServiceInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeSearchController extends Controller
{
    /**
     * @param Request $request
     * @param BrandServiceInterface $brandService
     * @param CategoryServiceInterface $categoryService
     * @param ProductServiceInterface $productService
     * @return JsonResponse|SearchProductResource
     */
    public function products(
        Request                  $request,
        BrandServiceInterface    $brandService,
        CategoryServiceInterface $categoryService,
        ProductServiceInterface  $productService
    ): JsonResponse|SearchProductResource
    {
        $searchText = $request->string('text');

        if (empty($searchText)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => null,
            ]);
        }

        $limit = 9;

        $filter = new Filter();
        $filter
            ->setSearchText($searchText)
            ->setLimit($limit);

        $brands = $brandService->getPublishedBrands($filter);
        $categories = $categoryService->getPublishedCategories($filter);
        //
        $filter = new HomeProductFilter();
        $filter
            ->setSearchText($searchText)
            ->setLimit($limit);
        $products = $productService->getFilteredProducts($filter, true);

        return new SearchProductResource($products, $brands, $categories);
    }

    /**
     * @param Request $request
     * @param BlogCategoryServiceInterface $blogCategoryService
     * @param BlogServiceInterface $blogService
     * @return JsonResponse|SearchBlogResource
     */
    public function blogs(
        Request                      $request,
        BlogCategoryServiceInterface $blogCategoryService,
        BlogServiceInterface         $blogService
    ): JsonResponse|SearchBlogResource
    {
        $searchText = $request->string('text');

        if (empty($searchText)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => null,
            ]);
        }

        $limit = 8;

        $filter = new Filter();
        $filter
            ->setSearchText($searchText)
            ->setLimit($limit);
        $categories = $blogCategoryService->getPublishedHighPriorityCategories($filter);
        //
        $filter = new HomeBlogFilter();
        $filter
            ->setSearchText($searchText)
            ->setLimit($limit);
        $blogs = $blogService->getFilteredBlogs($filter, true);

        return new SearchBlogResource($blogs, $categories);
    }
}
