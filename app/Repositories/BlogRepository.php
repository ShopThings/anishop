<?php

namespace App\Repositories;

use App\Enums\Blogs\BlogOrderTypesEnum;
use App\Enums\Blogs\BlogVotingTypesEnum;
use App\Http\Requests\Filters\HomeBlogFilter;
use App\Models\Blog;
use App\Models\BlogVote;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class BlogRepository extends Repository implements BlogRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(
        Blog               $model,
        protected BlogVote $blogVote
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getBlogsSearchFilterPaginated(
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query->when($search, function (Builder $query, string $search) {
            $query
                ->withWhereHas('category', function ($q) use ($search) {
                    $q->orWhereLike([
                        'escaped_name',
                        'keywords',
                    ], $search);
                })
                ->orWhereLike([
                    'blogs.escaped_title',
                    'blogs.keywords',
                ], $search);
        });

        if ($filter instanceof HomeBlogFilter) {
            // need published stuffs
            $query = $query->published();
            $query = $this->_getBlogOrderEquivalent($query, $filter->getBlogOrder());

            $tag = $filter->getTag();
            $archive = $filter->getArchive();
            $category = $filter->getCategory();

            $query
                ->when($category, function (Builder $query, int $category) {
                    $query->where('blogs.category_id', $category);
                })
                ->when($tag, function (Builder $query, $tag) {
                    foreach (Arr::wrap($tag) as $t) {
                        $query->orWhereLike(['blogs.keywords'], $t);
                    }
                })
                ->when($archive, function (Builder $query, $archive) {
                    $query->whereBetween('blogs.created_at', [
                        $archive,
                        $archive->addMonth()
                    ]);
                });
        }

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function isBlogVoted(int $userId, int $blogId): BlogVotingTypesEnum
    {
        $vote = $this->blogVote->newQuery()
            ->where('user_id', $userId)
            ->where('blog_id', $blogId)
            ->first();

        if (!$vote instanceof BlogVote) return BlogVotingTypesEnum::NOT_SET;

        return !!$vote->is_voted ? BlogVotingTypesEnum::VOTED : BlogVotingTypesEnum::NOT_VOTED;
    }

    /**
     * @inheritDoc
     */
    public function toggleBlogVote(int $userId, int $blogId, BlogVotingTypesEnum $vote): bool
    {
        if ($vote === BlogVotingTypesEnum::NOT_SET) {
            return !!$this->blogVote->newQuery()
                ->where('user_id', $userId)
                ->where('blog_id', $blogId)
                ->delete();
        } else {
            $model = $this->blogVote->newQuery()
                ->updateOrCreate([
                    'user_id' => $userId,
                    'blog_id' => $blogId,
                ], [
                    'is_voted' => $vote === BlogVotingTypesEnum::VOTED,
                ]);

            return $model instanceof BlogVote;
        }
    }

    /**
     * @inheritDoc
     */
    public function getArchive(): Collection
    {
        return $this->model->newQuery()
            ->groupBy(['year', 'month'])
            ->get([
                'YEAR(created_at) as year',
                'MONTH(created_at) as month',
                'COUNT(*) as count',
                'created_at',
            ]);
    }

    /**
     * @param Builder $query
     * @param BlogOrderTypesEnum|null $order
     * @return Builder
     */
    private function _getBlogOrderEquivalent(Builder $query, ?BlogOrderTypesEnum $order): Builder
    {
        if (null === $order) return $query->orderBy('id', 'desc');

        $visitableTable = config('visitor.table_name');

        return match ($order) {
            BlogOrderTypesEnum::NEWEST => $query->orderBy('id', 'desc'),
            BlogOrderTypesEnum::OLDEST => $query->orderBy('id', 'asc'),
            BlogOrderTypesEnum::MOST_VIEWED => $query->select(['blogs.*'])
                ->selectRaw('COUNT(DISTINCT(' . $visitableTable . '.ip)) AS unique_visit_count')
                ->leftJoin(
                    $visitableTable,
                    function ($join) use ($visitableTable) {
                        $join->on('blogs.id', $visitableTable . '.visitable_id')
                            ->where('visitable_type', Blog::class);
                    }
                )
                ->orderBy('unique_visit_count', 'desc')
                ->groupBy('blogs.id'),
        };
    }
}
