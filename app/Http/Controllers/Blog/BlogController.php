<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\User;
use App\Services\Contracts\BlogServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class BlogController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param BlogServiceInterface $service
     */
    public function __construct(
        protected BlogServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return BlogResource::collection($this->service->getBlogs($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد بلاگ با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد بلاگ',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Blog $blog
     * @return BlogResource
     * @throws AuthorizationException
     */
    public function show(Blog $blog): BlogResource
    {
        $this->authorize('view', $blog);
        return new BlogResource($blog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlogRequest $request
     * @param Blog $blog
     * @return BlogResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse|BlogResource
    {
        $this->authorize('update', $blog);

        $validated = $request->validated();
        unset($validated['escaped_title']);
        unset($validated['slug']);
        $model = $this->service->updateById($blog->id, $validated);

        if (!is_null($model)) {
            return new BlogResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش بلاگ',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Blog $blog
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Blog $blog): JsonResponse
    {
        $this->authorize('delete', $blog);

        $permanent = $request->user()->id === $blog->creator()?->id;
        $res = $this->service->deleteById($blog->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
