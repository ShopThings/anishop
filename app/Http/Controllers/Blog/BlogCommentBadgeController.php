<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogBadgeRequest;
use App\Http\Requests\UpdateBlogBadgeRequest;
use App\Http\Resources\BlogBadgeResource;
use App\Models\BlogCommentBadge;
use App\Services\Contracts\BlogBadgeServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
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
        $this->policyModel = BlogCommentBadge::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', BlogCommentBadge::class);
        return BlogBadgeResource::collection($this->service->getBadges($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogBadgeRequest $request
     * @return JsonResponse
     */
    public function store(StoreBlogBadgeRequest $request): JsonResponse
    {
        Gate::authorize('create', BlogCommentBadge::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد برچسب با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد برچسب',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param BlogCommentBadge $blogBadge
     * @return BlogBadgeResource
     */
    public function show(BlogCommentBadge $blogBadge): BlogBadgeResource
    {
        Gate::authorize('view', $blogBadge);
        return new BlogBadgeResource($blogBadge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlogBadgeRequest $request
     * @param BlogCommentBadge $blogBadge
     * @return BlogBadgeResource|JsonResponse
     */
    public function update(UpdateBlogBadgeRequest $request, BlogCommentBadge $blogBadge): JsonResponse|BlogBadgeResource
    {
        Gate::authorize('update', $blogBadge);

        $validated = filter_validated_data($request->validated(), [
            'title',
            'color_hex',
            'is_published',
        ]);
        $model = $this->service->updateById($blogBadge->id, $validated);

        if (!is_null($model)) {
            return new BlogBadgeResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش برچسب',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param BlogCommentBadge $blogBadge
     * @return JsonResponse
     */
    public function destroy(Request $request, BlogCommentBadge $blogBadge): JsonResponse
    {
        Gate::authorize('delete', $blogBadge);

        $permanent = $request->user()->id === $blogBadge->creator?->id;
        $res = $this->service->deleteById($blogBadge->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
