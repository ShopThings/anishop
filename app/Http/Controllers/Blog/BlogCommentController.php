<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCommentRequest;
use App\Http\Requests\UpdateBlogCommentRequest;
use App\Http\Resources\BlogCommentResource;
use App\Http\Resources\BlogCommentSingleResource;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
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
        $this->policyModel = BlogComment::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @param Blog $blog
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter, Blog $blog): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', BlogComment::class);
        return BlogCommentResource::collection($this->service->getComments(blogId: $blog->id, filter: $filter));
    }

    /**
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function all(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', BlogComment::class);
        return BlogCommentResource::collection($this->service->getAllComments($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogCommentRequest $request
     * @return JsonResponse
     */
    public function store(StoreBlogCommentRequest $request): JsonResponse
    {
        Gate::authorize('create', BlogComment::class);

        $validated = filter_validated_data($request->validated(), [
            'blog',
            'badge',
            'comment',
            'description',
        ]);
        $validated['condition'] = CommentConditionsEnum::ACCEPTED->value;
        $validated['status'] = CommentStatusesEnum::READ->value;
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد دیدگاه با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد دیدگاه',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param BlogComment $blogComment
     * @return BlogCommentSingleResource
     */
    public function show(BlogComment $blogComment): BlogCommentSingleResource
    {
        Gate::authorize('view', $blogComment);
        return new BlogCommentSingleResource($blogComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlogCommentRequest $request
     * @param BlogComment $blogComment
     * @return BlogCommentResource|JsonResponse
     */
    public function update(UpdateBlogCommentRequest $request, BlogComment $blogComment): BlogCommentResource|JsonResponse
    {
        Gate::authorize('update', $blogComment);

        $validated = filter_validated_data($request->validated(), [
            'badge',
            'condition',
            'status',
        ]);
        $model = $this->service->updateById($blogComment->id, $validated);

        if (!is_null($model)) {
            return new BlogCommentResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش دیدگاه',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param BlogComment $blogComment
     * @return JsonResponse
     */
    public function destroy(Request $request, BlogComment $blogComment): JsonResponse
    {
        Gate::authorize('delete', $blogComment);

        $permanent = $request->user()->id === $blogComment->creator?->id;
        $res = $this->service->deleteById($blogComment->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
