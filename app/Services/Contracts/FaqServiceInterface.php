<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FaqServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getFaqs(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getFaqsCount(): int;

    /**
     * @param Filter $filter
     * @return Collection
     */
    public function getHomeFaqs(Filter $filter): Collection;
}
