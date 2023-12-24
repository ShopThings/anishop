<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCommentVoteRequest;
use App\Http\Resources\Home\CommentResource;
use App\Models\Comment;
use App\Models\Product;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Support\Filter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class HomeCommentController extends Controller
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
     * @param Filter $filter
     * @param Product $product
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter, Product $product): AnonymousResourceCollection
    {
        return CommentResource::collection($this->service->getComments(
            productId: $product->id,
            filter: $filter
        ));
    }

    /**
     * @param Request $request
     * @param Product $product
     * @param Comment $comment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function report(Request $request, Product $product, Comment $comment): JsonResponse
    {
        $this->authorize('reportComment', $product);

        // check for previous report footprint
        $cookieName = 'comment_report_' . $comment->id;
        $ip = $request->ip();

        $commentCookie = Cookie::get($cookieName);
        if ($commentCookie && $commentCookie === $ip) {
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'گزارش شما ثبت شده است.',
            ], ResponseCodes::HTTP_CONFLICT);
        }

        // set new report footprint
        Cookie::make($cookieName, $ip);

        $status = $this->service->reportComment($comment);

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'گزارش شما ثبت شد.',
            ], ResponseCodes::HTTP_OK);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت گزارش',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param ProductCommentVoteRequest $request
     * @param Product $product
     * @param Comment $comment
     * @return JsonResponse
     */
    public function vote(ProductCommentVoteRequest $request, Product $product, Comment $comment): JsonResponse
    {
        try {
            $this->authorize('voteComment', $product);
        } catch (AuthorizationException) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'لطفا ابتدا در به پنل کاربری خود وارد شوید و سپس دوباره تلاش نمایید.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check for previous vote footprint
        // NOTE: this is NOT so reliable, better way is to have separate table
        $cookieName = 'comment_vote_' . $comment->id . '_' . $request->user()->id;
        $cookieVal = $request->ip() . '_' . $request->user()->id;

        $commentCookie = Cookie::get($cookieName);
        if ($commentCookie && $commentCookie === $cookieVal) {
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'رأی شما ثبت شده است.',
            ], ResponseCodes::HTTP_CONFLICT);
        }

        // set new vote footprint
        Cookie::make($cookieName, $cookieVal);

        $status = $this->service->voteComment($comment, $request->validated('vote'));

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'رأی شما ثبت شد.',
            ], ResponseCodes::HTTP_OK);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت رأی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
