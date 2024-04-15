<?php

namespace App\Repositories\Contracts;

use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ReportRepositoryInterface
{
    /**
     * @param Filter $filter
     * @param array|null $reportQuery
     * @return Collection|LengthAwarePaginator
     */
    public function getUsersForReport(
        Filter $filter,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator;

    /**
     * @return array
     */
    public function getUsersQueryBuilderInfo(): array;

    /**
     * @return array
     */
    public function getProductsQueryBuilderInfo(): array;

    /**
     * @return array
     */
    public function getOrdersQueryBuilderInfo(): array;
}
