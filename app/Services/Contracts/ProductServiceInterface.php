<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Support\Filter;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
     * @return int
     */
    public function getProductsCount(): int;

    /**
     * @param GetterExpressionInterface $where
     * @return Model|null
     */
    public function getSingleProduct(GetterExpressionInterface $where): ?Model;

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
     * @param HomeProductFilter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getFilteredProducts(HomeProductFilter $filter): Collection|LengthAwarePaginator;

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
    public function createGalley(int $productId, array $images): bool;

    /**
     * @param int $productId
     * @param array $products
     * @return bool
     */
    public function createRelatedProducts(int $productId, array $products): bool;

    /**
     * @param int $productId
     * @param array $products
     * @return Model|Collection
     */
    public function modifyProducts(int $productId, array $products): Model|Collection;

    /**
     * @param array $ids
     * @param array $attributes
     * @return bool
     */
    public function updateBatchInfo(array $ids, array $attributes): bool;

    /**
     * @param array $ids
     * @param int $percentage
     * @param ChangeMultipleProductPriceTypesEnum $changeType
     * @return bool
     */
    public function updateBatchPrice(
        array                               $ids,
        int                                 $percentage,
        ChangeMultipleProductPriceTypesEnum $changeType
    ): bool;
}
