<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface FestivalServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getFestivals(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return Collection
     */
    public function getHomeFestivals(): Collection;

    /**
     * @param int $festivalId
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getFestivalProducts(
        int    $festivalId,
        Filter $filter
    ): Collection|LengthAwarePaginator;

    /**
     * @param $productId
     * @param $festivalId
     * @param $discountPercentage
     * @return Model|null
     */
    public function addProductToFestival($productId, $festivalId, $discountPercentage): ?Model;

    /**
     * @param $productId
     * @param $festivalId
     * @return bool
     */
    public function removeProductFromFestival($productId, $festivalId): bool;

    /**
     * @param $festivalId
     * @param array $ids
     * @return bool
     */
    public function removeProductsFromFestival($festivalId, array $ids): bool;

    /**
     * @param $categoryId
     * @param $festivalId
     * @param $discountPercentage
     * @return Collection
     */
    public function addCategoryToFestival($categoryId, $festivalId, $discountPercentage): Collection;

    /**
     * @param $categoryId
     * @param $festivalId
     * @return bool
     */
    public function removeCategoryFromFestival($categoryId, $festivalId): bool;
}
