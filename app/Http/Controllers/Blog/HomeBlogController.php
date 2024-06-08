<?php

namespace App\Http\Controllers\Blog;

use App\Enums\Blogs\BlogVotingTypesEnum;
use App\Enums\DatabaseEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\HomeBlogFilter;
use App\Http\Requests\UpdateHomeBlogRequest;
use App\Http\Resources\Home\ArchiveResource;
use App\Http\Resources\Home\BlogCategoryResource as HomeBlogCategoryResource;
use App\Http\Resources\Home\BlogResource as HomeBLogResource;
use App\Http\Resources\Home\BlogSingleResource as HomeBlogSingleResource;
use App\Models\Blog;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Services\Contracts\BlogServiceInterface;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeBlogController extends Controller
{
    /**
     * @param BlogServiceInterface $service
     */
    public function __construct(
        protected BlogServiceInterface $service
    )
    {
    }

    /**
     * @param HomeBlogFilter $filter
     * @return AnonymousResourceCollection
     */
    public function index(HomeBlogFilter $filter): AnonymousResourceCollection
    {
        return HomeBlogResource::collection($this->service->getFilteredBlogs($filter));
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function homeLatestBlogs(): AnonymousResourceCollection
    {
        $filter = new HomeBlogFilter();
        $filter
            ->setLimit(3)
            ->setOrder([
                'created_at' => 'desc',
                'id' => 'desc',
            ]);
        return HomeBLogResource::collection($this->service->getBlogs($filter));
    }

    /**
     * This will use 'log_visit' to log too
     *
     * @param Blog $blog
     * @return JsonResponse|HomeBlogSingleResource
     */
    public function show(Blog $blog): JsonResponse|HomeBlogSingleResource
    {
        if (Gate::denies('isPubliclyAccessible', $blog)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'امکان مشاهده یلاگ وجود ندارد.',
            ]);
        }

        return new HomeBlogSingleResource($blog);
    }

    /**
     * @param $blog
     * @return HomeBLogResource
     */
    public function minifiedShow($blog): HomeBlogResource
    {
        $where = new WhereBuilder();
        $where
            ->whereEqual('is_published', DatabaseEnum::DB_YES)
            ->group(function (WhereBuilderInterface $builder) use ($blog) {
                $builder
                    ->orWhereEqual('id', $blog)
                    ->orWhereEqual('slug', $blog);
            });

        $model = $this->service->getSingleBlog($where->build());

        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        return new HomeBlogResource($blog);
    }

    /**
     * @param UpdateHomeBlogRequest $request
     * @param Blog $blog
     * @return JsonResponse
     */
    public function vote(UpdateHomeBlogRequest $request, Blog $blog): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'لطفا ابتدا به پنل خود وارد شوید.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        $vote = $request->validated('vote');
        $isVoted = $this->service->toggleVote($user->id, $blog->id, BlogVotingTypesEnum::tryFrom($vote));

        if ($isVoted) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'رأی شما با موفقیت ثبت شد.',
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ثبت رأی',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function archive(): AnonymousResourceCollection
    {
        return ArchiveResource::collection($this->service->getArchive());
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function mainSlider(): AnonymousResourceCollection
    {
        return HomeBlogResource::collection($this->service->getMainSlider());
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function mainSideSlides(): AnonymousResourceCollection
    {
        return HomeBLogResource::collection($this->service->getMainSideSlides());
    }

    /**
     * @param BlogCategoryServiceInterface $service
     * @return AnonymousResourceCollection
     */
    public function popularCategories(BlogCategoryServiceInterface $service): AnonymousResourceCollection
    {
        return HomeBlogCategoryResource::collection($service->getPublishedHighPriorityCategories());
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function mostViewed(): AnonymousResourceCollection
    {
        return HomeBlogResource::collection($this->service->getLatestMostViewedBlogs());
    }
}
