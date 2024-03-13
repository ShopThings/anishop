<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FestivalRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $festivalId
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getFestivalProductsSearchFilterPaginated(
        int    $festivalId,
        array  $columns = ['*'],
        Filter $filter = null,
    ): Collection|LengthAwarePaginator;
}
