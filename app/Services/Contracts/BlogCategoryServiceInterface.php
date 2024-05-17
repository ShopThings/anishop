<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BlogCategoryServiceInterface extends ServiceInterface
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
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getPublishedHighPriorityCategories(Filter $filter = null): Collection|LengthAwarePaginator;
}
