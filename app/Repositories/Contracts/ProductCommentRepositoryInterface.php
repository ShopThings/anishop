<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductCommentRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @param array $columns
     * @param string|null $search
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getCommentsSearchFilterPaginated(
        int     $productId,
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator;
}
