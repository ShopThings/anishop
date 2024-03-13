<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductCommentRequest;
use App\Http\Resources\ProductCommentResource;
use App\Http\Resources\ProductCommentSingleResource;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class CommentController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param ProductCommentServiceInterface $service
     */
    public function __construct(
        protected ProductCommentServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @param Product $product
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter, Product $product): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return ProductCommentResource::collection($this->service->getComments(
            productId: $product->id,
            filter: $filter
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return ProductCommentSingleResource
     * @throws AuthorizationException
     */
    public function show(Comment $comment): ProductCommentSingleResource
    {
        $this->authorize('view', $comment);
        return new ProductCommentSingleResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductCommentRequest $request
     * @param Comment $comment
     * @return ProductCommentResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateProductCommentRequest $request, Comment $comment): ProductCommentResource|JsonResponse
    {
        $this->authorize('update', $comment);

        $validated = $request->validated(['answer', 'condition', 'status']);
        $model = $this->service->updateById($comment->id, $validated);

        if (!is_null($model)) {
            return new ProductCommentResource($model);
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
     * @param Comment $comment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $permanent = $request->user()->id === $comment->creator?->id;
        $res = $this->service->deleteById($comment->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
