<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Http\Requests\Filters\HomeProductSideFilter;
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
     * @param HomeProductSideFilter $filter
     * @return Collection
     */
    public function getFilterColorsAndSizes(HomeProductSideFilter $filter): Collection;

    /**
     * @param HomeProductSideFilter $filter
     * @return array
     */
    public function getFilterPriceRange(HomeProductSideFilter $filter): array;

    /**
     * @param HomeProductSideFilter $filter
     * @return Collection
     */
    public function getDynamicFilters(HomeProductSideFilter $filter): Collection;

    /**
     * @param array $products
     * @return Model|Collection
     */
    public function updateOrCreateProducts(array $products): Model|Collection;

    /**
     * @param $id
     * @param int $percentage
     * @param ChangeMultipleProductPriceTypesEnum $changeType
     * @return bool
     */
    public function updatePriceUsingPercentage(
        $id,
        int $percentage,
        ChangeMultipleProductPriceTypesEnum $changeType
    ): bool;
}
