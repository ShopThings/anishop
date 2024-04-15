<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Http\Resources\BlogCategoryResource;
use App\Models\BlogCategory;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class BlogCategoryController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param BlogCategoryServiceInterface $service
     */
    public function __construct(
        protected BlogCategoryServiceInterface $service
    )
    {
        $this->considerDeletable = true;
        $this->policyModel = BlogCategory::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', BlogCategory::class);
        return BlogCategoryResource::collection($this->service->getCategories($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreBlogCategoryRequest $request): JsonResponse
    {
        Gate::authorize('create', BlogCategory::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد دسته‌بندی با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد دسته‌بندی',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param BlogCategory $blogCategory
     * @return BlogCategoryResource
     */
    public function show(BlogCategory $blogCategory): BlogCategoryResource
    {
        Gate::authorize('view', $blogCategory);
        return new BlogCategoryResource($blogCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlogCategoryRequest $request
     * @param BlogCategory $blogCategory
     * @return BlogCategoryResource|JsonResponse
     */
    public function update(
        UpdateBlogCategoryRequest $request,
        BlogCategory              $blogCategory
    ): JsonResponse|BlogCategoryResource
    {
        Gate::authorize('update', $blogCategory);

        $validated = $request->validated();
        unset($validated['is_deletable']);
        $model = $this->service->updateById($blogCategory->id, $validated);

        if (!is_null($model)) {
            return new BlogCategoryResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش رنگ',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param BlogCategory $blogCategory
     * @return JsonResponse
     */
    public function destroy(Request $request, BlogCategory $blogCategory): JsonResponse
    {
        Gate::authorize('delete', $blogCategory);

        $permanent = $request->user()->id === $blogCategory->creator?->id;
        $res = $this->service->deleteById($blogCategory->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
