<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\HomeBlogCommentFilter;
use App\Http\Resources\Home\BlogCommentResource;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Services\Contracts\BlogCommentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Gate;
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
     * @param HomeBlogCommentFilter $filter
     * @param Blog $blog
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(HomeBlogCommentFilter $filter, Blog $blog): JsonResponse|AnonymousResourceCollection
    {
        if (Gate::denies('isPubliclyAccessible', $blog)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'امکان مشاهده کامنت‌ها وجود ندارد.',
            ]);
        }

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
     */
    public function report(Request $request, Blog $blog, BlogComment $comment): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'لطفا ابتدا در به پنل کاربری خود وارد شوید و سپس دوباره تلاش نمایید.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        Gate::authorize('reportComment', [$comment, $blog]);

        // check for previous report footprint
        $cookieName = 'blog_comment_report_' . $comment->id;
        $ip = $request->ip();

        $commentCookie = Cookie::get($cookieName);
        if ($commentCookie && $commentCookie === $ip) {
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'گزارش شما ثبت شده است.',
            ], ResponseCodes::HTTP_CREATED);
        }

        // set new report footprint
        Cookie::make($cookieName, $ip);

        $status = $this->service->reportComment($comment);

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'گزارش شما ثبت شد.',
            ], ResponseCodes::HTTP_OK);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ثبت گزارش',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
