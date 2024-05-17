<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BrandServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getBrands(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getBrandsCount(): int;

    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getPublishedBrands(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return Collection
     */
    public function getSliderBrands(): Collection;
}
