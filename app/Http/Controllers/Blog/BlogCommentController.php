<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCommentRequest;
use App\Http\Requests\UpdateBlogCommentRequest;
use App\Http\Resources\BlogCommentResource;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\User;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class BlogCommentController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param BlogCommentServiceInterface $service
     */
    public function __construct(
        protected BlogCommentServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @param Blog $blog
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter, Blog $blog): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return BlogCommentResource::collection($this->service->getComments(blogId: $blog->id, filter: $filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogCommentRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreBlogCommentRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated([
            'blog',
            'badge',
            'comment',
            'answer_to',
            'description',
        ]);
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد دیدگاه با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد دیدگاه',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param BlogComment $blogComment
     * @return BlogCommentResource
     * @throws AuthorizationException
     */
    public function show(BlogComment $blogComment): BlogCommentResource
    {
        $this->authorize('view', $blogComment);
        return new BlogCommentResource($blogComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlogCommentRequest $request
     * @param BlogComment $blogComment
     * @return BlogCommentResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateBlogCommentRequest $request, BlogComment $blogComment): BlogCommentResource|JsonResponse
    {
        $this->authorize('update', $blogComment);

        $validated = $request->validated([
            'badge',
            'condition',
            'status',
        ]);
        $model = $this->service->updateById($blogComment->id, $validated);

        if (!is_null($model)) {
            return new BlogCommentResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش دیدگاه',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param BlogComment $blogComment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, BlogComment $blogComment): JsonResponse
    {
        $this->authorize('delete', $blogComment);

        $permanent = $request->user()->id === $blogComment->creator()?->id;
        $res = $this->service->deleteById($blogComment->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
