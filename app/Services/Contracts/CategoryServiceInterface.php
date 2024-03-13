<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getCategories(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param Filter $filter
     * @return Collection
     */
    public function getHomeCategories(Filter $filter): Collection;

    /**
     * @return Collection
     */
    public function getSliderCategories(): Collection;
}
