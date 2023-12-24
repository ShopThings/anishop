<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Models\BlogComment;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BlogCommentRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $blogId
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getCommentsSearchFilterPaginated(
        int    $blogId,
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
}
