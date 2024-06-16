<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * @param int $productId
     * @param int $festivalId
     * @param $discountPercentage
     * @return Builder|Model
     */
    public function addProductToFestival(int $productId, int $festivalId, $discountPercentage): Builder|Model;

    /**
     * @param int $productId
     * @param int $festivalId
     * @return bool
     */
    public function removeProductFromFestival(int $productId, int $festivalId): bool;

    /**
     * @param int $festivalId
     * @param array $ids
     * @return bool
     */
    public function removeProductsFromFestival(int $festivalId, array $ids): bool;
}
