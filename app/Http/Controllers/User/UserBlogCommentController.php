<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserBlogCommentRequest;
use App\Http\Requests\UpdateUserBlogCommentRequest;
use App\Http\Resources\User\UserBlogCommentResource;
use App\Http\Resources\User\UserBlogCommentSingleResource;
use App\Models\BlogComment;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserBlogCommentController extends Controller
{
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
     * @param Request $request
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, Filter $filter): AnonymousResourceCollection
    {
        return UserBlogCommentResource::collection($this->service->getUserComments(
            userId: $request->user()->id,
            filter: $filter
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserBlogCommentRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserBlogCommentRequest $request): JsonResponse
    {
        $validated = filter_validated_data($request->validated(), [
            'blog',
            'comment',
            'description',
        ]);
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'دیدگاه شما با موفقیت ثبت شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ثبت دیدگاه',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param BlogComment $comment
     * @return UserBlogCommentSingleResource
     */
    public function show(BlogComment $comment): UserBlogCommentSingleResource
    {
        return new UserBlogCommentSingleResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserBlogCommentRequest $request
     * @param BlogComment $comment
     * @return UserBlogCommentSingleResource|JsonResponse
     */
    public function update(
        UpdateUserBlogCommentRequest $request,
        BlogComment $comment
    ): UserBlogCommentSingleResource|JsonResponse
    {
        $validated = $request->validated('description');
        $model = $this->service->updateById($comment->id, $validated);

        if (!is_null($model)) {
            return new UserBlogCommentSingleResource($model);
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
     * @param BlogComment $comment
     * @return JsonResponse
     */
    public function destroy(Request $request, BlogComment $comment): JsonResponse
    {
        $res = $this->service->deleteUserCommentById($request->user()->id, $comment->id);

        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
