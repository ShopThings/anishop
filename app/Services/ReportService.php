<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReportService extends Service implements ReportServiceInterface
{
    public function __construct(
        protected ReportRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getUsersForReport(
        Filter $filter,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator
    {
        $filter->setSearchText(null);

        return $this->repository->getUsersForReport(
            filter: $filter,
            reportQuery: $reportQuery
        );
    }

    /**
     * @inheritDoc
     */
    public function getUsersQueryBuilderInfo(): array
    {
        return $this->repository->getUsersQueryBuilderInfo();
    }

    /**
     * @inheritDoc
     */
    public function getProductsQueryBuilderInfo(): array
    {
        return $this->repository->getProductsQueryBuilderInfo();
    }

    /**
     * @inheritDoc
     */
    public function getOrdersQueryBuilderInfo(): array
    {
        return $this->repository->getOrdersQueryBuilderInfo();
    }
}
