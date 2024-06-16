<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductAttributeCategoryServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getAttributeCategories(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getAttributeCategoriesCount(): int;

    /**
     * @param int $productId
     * @return Collection|null
     */
    public function getProductAttributeCategories(int $productId): Collection|null;
}
