<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Blogs\BlogVotingTypesEnum;
use App\Http\Requests\Filters\HomeBlogFilter;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BlogServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getBlogs(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param HomeBlogFilter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getFilteredBlogs(HomeBlogFilter $filter): Collection|LengthAwarePaginator;

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
