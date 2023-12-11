<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
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
}
