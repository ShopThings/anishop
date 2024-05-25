<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ReportServiceInterface extends ServiceInterface
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
     * @param Filter $filter
     * @param array|null $reportQuery
     * @return Collection|LengthAwarePaginator
     */
    public function getProductsForReport(
        Filter $filter,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param Filter $filter
     * @param array|null $reportQuery
     * @return Collection|LengthAwarePaginator
     */
    public function getOrdersForReport(
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
