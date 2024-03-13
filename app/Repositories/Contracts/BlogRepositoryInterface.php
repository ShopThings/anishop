<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Blogs\BlogVotingTypesEnum;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BlogRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getBlogsSearchFilterPaginated(
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param int $userId
     * @param int $blogId
     * @return BlogVotingTypesEnum
     */
    public function isBlogVoted(int $userId, int $blogId): BlogVotingTypesEnum;

    /**
     * It will add vote(like/dislike) or remove it from DB when <code>$vote</code> is <b>null</b>
     *
     * @param int $userId
     * @param int $blogId
     * @param BlogVotingTypesEnum $vote
     * @return bool
     */
    public function toggleBlogVote(int $userId, int $blogId, BlogVotingTypesEnum $vote): bool;

    /**
     * @return Collection
     */
    public function getArchive(): Collection;
}
