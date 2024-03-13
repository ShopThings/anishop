<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogBadgeRequest;
use App\Http\Requests\UpdateBlogBadgeRequest;
use App\Http\Resources\BlogBadgeResource;
use App\Models\BlogCommentBadge;
use App\Models\User;
use App\Services\Contracts\BlogBadgeServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class BlogCommentBadgeController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param BlogBadgeServiceInterface $service
     */
    public function __construct(
        protected BlogBadgeServiceInterface $service
    )
    {
        $this->considerDeletable = true;
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
        return BlogBadgeResource::collection($this->service->getBadges($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogBadgeRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreBlogBadgeRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد برچسب با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد برچسب',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param BlogCommentBadge $blogBadge
     * @return BlogBadgeResource
     * @throws AuthorizationException
     */
    public function show(BlogCommentBadge $blogBadge): BlogBadgeResource
    {
        $this->authorize('view', $blogBadge);
        return new BlogBadgeResource($blogBadge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlogBadgeRequest $request
     * @param BlogCommentBadge $blogBadge
     * @return BlogBadgeResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateBlogBadgeRequest $request, BlogCommentBadge $blogBadge): JsonResponse|BlogBadgeResource
    {
        $this->authorize('update', $blogBadge);

        $validated = $request->validated([
            'title',
            'color_hex',
            'is_published',
        ]);
        $model = $this->service->updateById($blogBadge->id, $validated);

        if (!is_null($model)) {
            return new BlogBadgeResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش برچسب',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param BlogCommentBadge $blogBadge
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, BlogCommentBadge $blogBadge): JsonResponse
    {
        $this->authorize('delete', $blogBadge);

        $permanent = $request->user()->id === $blogBadge->creator?->id;
        $res = $this->service->deleteById($blogBadge->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
