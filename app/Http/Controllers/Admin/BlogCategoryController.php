<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Http\Resources\BlogCategoryResource;
use App\Models\BlogCategory;
use App\Models\User;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class BlogCategoryController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

    /**
     * @param BlogCategoryServiceInterface $service
     */
    public function __construct(
        protected BlogCategoryServiceInterface $service
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

        return BlogCategoryResource::collection($this->service->getCategories(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogCategoryRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreBlogCategoryRequest $request)
    {
        $this->authorize('create', User::class);

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
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param BlogCategory $blogCategory
     * @return BlogCategoryResource
     * @throws AuthorizationException
     */
    public function show(BlogCategory $blogCategory)
    {
        $this->authorize('view', $blogCategory);
        return new BlogCategoryResource($blogCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlogCategoryRequest $request
     * @param BlogCategory $blogCategory
     * @return BlogCategoryResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $this->authorize('update', $blogCategory);

        $validated = $request->validated();
        unset($validated['is_deletable']);
        $model = $this->service->updateById($blogCategory->id, $validated);

        if (!is_null($model)) {
            return new BlogCategoryResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش رنگ',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param BlogCategory $blogCategory
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, BlogCategory $blogCategory)
    {
        $this->authorize('delete', $blogCategory);

        $permanent = $request->user()->id === $blogCategory->creator()?->id;
        $res = $this->service->deleteById($blogCategory->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
