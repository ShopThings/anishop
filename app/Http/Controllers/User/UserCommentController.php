<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCommentRequest;
use App\Http\Requests\UpdateProductCommentRequest;
use App\Http\Resources\User\UserProductCommentResource;
use App\Http\Resources\User\UserProductCommentSingleResource;
use App\Models\Comment;
use App\Models\Product;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserCommentController extends Controller
{
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
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, Filter $filter): AnonymousResourceCollection
    {
        return UserProductCommentResource::collection($this->service->getUserComments(
            userId: $request->user()->id,
            filter: $filter
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductCommentRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductCommentRequest $request, Product $product): JsonResponse
    {
        $validated = $request->validated(['pros', 'cons', 'description']);
        $model = $this->service->create(['product' => $product->id] + $validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'دیدگاه شما با موفقیت ثبت شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت دیدگاه',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return UserProductCommentSingleResource
     */
    public function show(Comment $comment): UserProductCommentSingleResource
    {
        return new UserProductCommentSingleResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductCommentRequest $request
     * @param Comment $comment
     * @return UserProductCommentSingleResource|JsonResponse
     */
    public function update(
        UpdateProductCommentRequest $request,
        Comment                     $comment
    ): UserProductCommentSingleResource|JsonResponse
    {
        $validated = $request->validated(['product', 'pros', 'const', 'description']);
        $model = $this->service->updateById($comment->id, $validated);

        if (!is_null($model)) {
            return new UserProductCommentSingleResource($model);
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
     */
    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $res = $this->service->deleteUserCommentById($request->user()->id, $comment->id);

        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
