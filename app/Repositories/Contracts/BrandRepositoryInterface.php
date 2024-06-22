<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ramsey\Collection\Collection;

interface BrandRepositoryInterface extends RepositoryInterface
{
    /**
     * @param Filter $filter
     * @param array $columns
     * @return Collection|LengthAwarePaginator
     */
    public function getPublishedFilteredBrands(
        Filter $filter,
        array  $columns = ['*']
    ): Collection|LengthAwarePaginator;
}
