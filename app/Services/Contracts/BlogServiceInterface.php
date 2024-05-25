<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Blogs\BlogVotingTypesEnum;
use App\Http\Requests\Filters\HomeBlogFilter;
use App\Support\Filter;
use App\Support\SitemapFetchInterface;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BlogServiceInterface extends ServiceInterface, SitemapFetchInterface
{
    /**
     * If filter is instance of HomeBlogFilter, it'll automatically fetch published ones
     *
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getBlogs(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getBlogsCount(): int;

    /**
     * @param GetterExpressionInterface $where
     * @return Model|null
     */
    public function getSingleBlog(GetterExpressionInterface $where): ?Model;

    /**
     * @param HomeBlogFilter $filter
     * @param bool $enforceProvidedFilterLimit
     * @return Collection|LengthAwarePaginator
     */
    public function getFilteredBlogs(
        HomeBlogFilter $filter,
        bool           $enforceProvidedFilterLimit = false
    ): Collection|LengthAwarePaginator;

    /**
     * @param int $userId
     * @param int $blogId
     * @param BlogVotingTypesEnum $vote
     * @return bool
     */
    public function toggleVote(int $userId, int $blogId, BlogVotingTypesEnum $vote): bool;

    /**
     * @return Collection
     */
    public function getArchive(): Collection;

    /**
     * @return Collection
     */
    public function getMainSlider(): Collection;

    /**
     * @return Collection
     */
    public function getMainSideSlides(): Collection;

    /**
     * @param int $limit
     * @return Collection
     */
    public function getLatestMostViewedBlogs(int $limit = 10): Collection;
}
