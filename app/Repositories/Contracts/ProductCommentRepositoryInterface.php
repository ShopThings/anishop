<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Comments\CommentVotingTypesEnum;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductCommentRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getCommentsSearchFilterPaginated(
        int    $productId,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param int $userId
     * @param Filter $filter
     * @param array $columns
     * @return Collection|LengthAwarePaginator
     */
    public function getUserCommentsFilterPaginated(
        int    $userId,
        Filter $filter,
        array  $columns = ['*']
    ): Collection|LengthAwarePaginator;

    /**
     * @return bool
     */
    public function reportComment(): bool;

    /**
     * @param CommentVotingTypesEnum $type
     * @return bool
     */
    public function voteComment(CommentVotingTypesEnum $type): bool;
}
