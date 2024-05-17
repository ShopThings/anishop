<?php

namespace App\Services;

use App\Enums\Charts\ChartPeriodsEnum;
use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Repositories\Contracts\PeriodicRepositoryInterface;
use App\Services\Contracts\PeriodicServiceInterface;
use App\Support\Service;
use Illuminate\Support\Collection;

class PeriodicService extends Service implements PeriodicServiceInterface
{
    public function __construct(
        protected PeriodicRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getTotalSale(): int
    {
        return $this->repository->getPeriodSale();
    }

    /**
     * @inheritDoc
     */
    public function getPeriodSale(ChartPeriodsEnum $period): int
    {
        return $this->repository->getPeriodSale($period);
    }

    /**
     * @inheritDoc
     */
    public function getPeriodUsersCount(ChartPeriodsEnum $period): array
    {
        return $this->repository->getPeriodUsersCount($period);
    }

    /**
     * @inheritDoc
     */
    public function getPeriodOrdersCount(ChartPeriodsEnum $period, string $statusCode): array
    {
        return $this->repository->getPeriodOrdersCount($period, $statusCode);
    }

    /**
     * @inheritDoc
     */
    public function getPeriodReturnOrdersCount(ChartPeriodsEnum $period, ReturnOrderStatusesEnum $status): array
    {
        return $this->repository->getPeriodReturnOrdersCount($period, $status);
    }

    /**
     * @inheritDoc
     */
    public function getPeriodMostSaleProductsCount(ChartPeriodsEnum $period, int $limit = 5): Collection
    {
        return $this->repository->getPeriodMostSaleProductsCount($period);
    }
}
