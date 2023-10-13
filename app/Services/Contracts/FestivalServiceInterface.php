<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface FestivalServiceInterface extends ServiceInterface
{
    /**
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getFestivals(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator;

    /**
     * @param int $festivalId
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getFestivalProducts(
        int     $festivalId,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
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
