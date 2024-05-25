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
     * @return int
     */
    public function getCategoriesCount(): int;

    /**
     * @param Filter $filter
     * @param bool $containMenuCategories
     * @return Collection
     */
    public function getPublishedCategories(Filter $filter, bool $containMenuCategories = false): Collection;

    /**
     * @return Collection
     */
    public function getSliderCategories(): Collection;
}
