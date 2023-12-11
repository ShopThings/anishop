<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Support\Filter;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $columns
     * @param Filter|null $filter
     * @param GetterExpressionInterface|null $where
     * @return Collection|LengthAwarePaginator
     */
    public function getProductsSearchFilterPaginated(
        array                     $columns = ['*'],
        Filter                    $filter = null,
        GetterExpressionInterface $where = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param array $products
     * @return Model|Collection
     */
    public function updateOrCreateProducts(array $products): Model|Collection;
}
