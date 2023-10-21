<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogBadgeRequest;
use App\Http\Requests\UpdateBlogBadgeRequest;
use App\Http\Resources\BlogBadgeResource;
use App\Http\Resources\OrderBadgeResource;
use App\Models\BlogCommentBadge;
use App\Models\User;
use App\Services\Contracts\BlogBadgeServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class BlogCommentBadgeController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

    /**
     * @param BlogBadgeServiceInterface $service
     */
    public function __construct(
        protected BlogBadgeServiceInterface $service
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

        return BlogBadgeResource::collection($this->service->getBadges(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlogBadgeRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreBlogBadgeRequest $request)
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
     * @param BlogCommentBadge $blogCommentBadge
     * @return BlogBadgeResource
     * @throws AuthorizationException
     */
    public function show(BlogCommentBadge $blogCommentBadge)
    {
        $this->authorize('view', $blogCommentBadge);
        return new BlogBadgeResource($blogCommentBadge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlogBadgeRequest $request
     * @param BlogCommentBadge $blogCommentBadge
     * @return BlogBadgeResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateBlogBadgeRequest $request, BlogCommentBadge $blogCommentBadge)
    {
        $this->authorize('update', $blogCommentBadge);

        $validated = $request->validated([
            'title',
            'color_hex',
            'is_published',
        ]);
        $model = $this->service->updateById($blogCommentBadge->id, $validated);

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
     * @param BlogCommentBadge $blogCommentBadge
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, BlogCommentBadge $blogCommentBadge)
    {
        $this->authorize('delete', $blogCommentBadge);

        $permanent = $request->user()->id === $blogCommentBadge->creator()?->id;
        $res = $this->service->deleteById($blogCommentBadge->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
