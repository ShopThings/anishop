<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface WeightPostPriceServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getPostPrices(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param int $weight
     * @return Model|null
     */
    public function getPostPriceByWeight(int $weight): ?Model;
}
