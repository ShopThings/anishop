<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CategoryImageRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $columns
     * @param string|null $search
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getCategoryImagesSearchFilterPaginated(
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator;
}
