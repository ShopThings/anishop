<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PaymentMethodServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getMethods(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getMethodsCount(): int;

    /**
     * @return Collection
     */
    public function getHomeMethods(): Collection;
}
