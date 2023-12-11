<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @param GetterExpressionInterface|null $where
     * @return Collection|LengthAwarePaginator
     */
    public function getProducts(
        Filter                    $filter,
        GetterExpressionInterface $where = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param int $productId
     * @param array $products
     * @return Model|Collection
     */
    public function modifyProducts(int $productId, array $products): Model|Collection;
}
