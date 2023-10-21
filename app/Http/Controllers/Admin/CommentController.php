<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductCommentRequest;
use App\Http\Resources\ProductCommentResource;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class CommentController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

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
     * @param Request $request
     * @param Product $product
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request, Product $product)
    {
        $this->authorize('viewAny', User::class);

        $params = $this->getPaginateParameters($request);

        return ProductCommentResource::collection($this->service->getComments(
            productId: $product->id,
            searchText: $params['text'],
            limit: $params['limit'],
            page: $params['page'],
            order: $params['order']
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return ProductCommentResource
     * @throws AuthorizationException
     */
    public function show(Comment $comment)
    {
        $this->authorize('view', $comment);
        return new ProductCommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductCommentRequest $request
     * @param Comment $comment
     * @return ProductCommentResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateProductCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validated(['condition', 'status']);
        $model = $this->service->updateById($comment->id, $validated);

        if (!is_null($model)) {
            return new ProductCommentResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش نظر',
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
    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $permanent = $request->user()->id === $comment->creator()?->id;
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
