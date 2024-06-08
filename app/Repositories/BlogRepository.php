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
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        array $columns = ['*'],
        Filter                    $filter = null,
        GetterExpressionInterface $where = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $onlyPublished = $filter->getOnlyPublished();

        $order = [];
        if (!$filter instanceof HomeBlogFilter) {
            $order = $filter->getOrder();
        }

        $query = $this->model->newQuery();
        $query
            ->with([
                'category',
                'image',
                'creator',
            ])
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query
                    ->when($filter->getRelationSearch(), function ($q) use ($search) {
                        $q->orWhereHas('category', function ($q) use ($search) {
                            $q->where(function ($q) use ($search) {
                                $q->orWhereLike([
                                    'escaped_name',
                                    'keywords',
                                ], $search);
                            });
                        });
                    })
                    ->orWhereLike([
                        'blogs.escaped_title',
                        'blogs.keywords',
                    ], $search);
            })
            ->when($onlyPublished && !$filter instanceof HomeBlogFilter, function (Builder $query) use ($onlyPublished) {
                $query->published();
            })
            ->when($where, function ($q, $where) {
                if (trim($where->getStatement()) !== '') {
                    $q->whereRaw($where->getStatement(), $where->getBindings());
                }
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
                        $archive->addMonth()->subDay()
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
            ->select([
                DB::raw('YEAR(created_at) year'),
                DB::raw('MONTH(created_at) month'),
                DB::raw('COUNT(*) as count'),
            ])
            ->groupBy(['year', 'month'])
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
    }

    /**
     * @param Builder $query
     * @param BlogOrderTypesEnum|null $order
     * @return Builder
     */
    private function _getBlogOrderEquivalent(Builder $query, ?BlogOrderTypesEnum $order): Builder
    {
        if (null === $order) return $query->orderBy('id', 'desc');

        return match ($order) {
            BlogOrderTypesEnum::OLDEST => $query->oldest('id'),
            BlogOrderTypesEnum::MOST_VIEWED => $this->_orderByMostViewed($query, config('visitor.table_name')),
            default => $query->latest('id'),
        };
    }

    //-------------------------------------------------------------------
    // Blog ordering criteria
    //-------------------------------------------------------------------

    /**
     * @param Builder $query
     * @param string $visitableTable
     * @return Builder
     */
    private function _orderByMostViewed(Builder $query, string $visitableTable): Builder
    {
        return $query->select(['blogs.*', 'subquery.unique_visit_count'])
            ->joinSub(
                $this->_getVisitCountSubquery($visitableTable, Blog::class),
                'subquery',
                'blogs.id',
                '=',
                'subquery.id'
            )
            ->orderBy('unique_visit_count', 'desc');
    }

    /**
     * @param string $visitableTable
     * @param string $visitableType
     * @return \Illuminate\Database\Query\Builder
     */
    private function _getVisitCountSubquery(string $visitableTable, string $visitableType): \Illuminate\Database\Query\Builder
    {
        return DB::table('blogs')
            ->select('blogs.id')
            ->selectRaw('COUNT(DISTINCT ' . $visitableTable . '.ip) AS unique_visit_count')
            ->leftJoin(
                $visitableTable,
                function ($join) use ($visitableTable, $visitableType) {
                    $join->on('blogs.id', $visitableTable . '.visitable_id')
                        ->where('visitable_type', $visitableType);
                }
            )
            ->groupBy('blogs.id');
    }
}
