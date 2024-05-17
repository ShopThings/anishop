<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ColorServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getColors(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getColorsCount(): int;
}
