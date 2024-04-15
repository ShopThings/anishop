<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CityPostPriceServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getPostPrices(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param int $cityId
     * @return Model|null
     */
    public function getPostPriceByCityId(int $cityId): ?Model;
}
