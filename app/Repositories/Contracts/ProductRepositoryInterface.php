<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Support\Filter;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
     * @param Filter|null $filter
     * @param array|null $reportQuery
     * @return Collection|LengthAwarePaginator
     */
    public function getProductsFilterPaginatedForReport(
        Filter $filter = null,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param string $code
     * @return Model|null
     */
    public function getProductVariantByCode(string $code): ?Model;

    /**
     * @param array $codes
     * @return Collection
     */
    public function getProductVariantsByCodes(array $codes): Collection;

    /**
     * @param HomeProductSideFilter $filter
     * @return Collection
     */
    public function getFilterBrands(HomeProductSideFilter $filter): Collection;

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
     * @param int $productId
     * @param array $images
     * @return bool
     */
    public function createGallery(int $productId, array $images): bool;

    /**
     * @param int $productId
     * @param array $products
     * @return bool
     */
    public function createRelatedProducts(int $productId, array $products): bool;

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
