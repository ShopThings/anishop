<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Home\BlogCommentResource;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Support\Filter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class HomeBlogCommentController extends Controller
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
     * @param Filter $filter
     * @param Blog $blog
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter, Blog $blog): AnonymousResourceCollection
    {
        return BlogCommentResource::collection($this->service->getComments(
            blogId: $blog->id,
            filter: $filter
        ));
    }

    /**
     * @param Request $request
     * @param Blog $blog
     * @param BlogComment $comment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function report(Request $request, Blog $blog, BlogComment $comment): JsonResponse
    {
        $this->authorize('reportComment', $blog);

        // check for previous report footprint
        $cookieName = 'blog_comment_report_' . $comment->id;
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
}
