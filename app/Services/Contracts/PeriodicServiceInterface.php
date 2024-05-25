<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Charts\ChartPeriodsEnum;
use App\Enums\Orders\ReturnOrderStatusesEnum;
use Illuminate\Support\Collection;

interface PeriodicServiceInterface extends ServiceInterface
{
    /**
     * @return int
     */
    public function getTotalSale(): int;

    /**
     * @param ChartPeriodsEnum $period
     * @return int
     */
    public function getPeriodSale(ChartPeriodsEnum $period): int;

    /**
     * Returns an array like:
     * [
     *   'label' => array,
     *   'data' => array,
     * ]
     *
     * @param ChartPeriodsEnum $period
     * @return array
     */
    public function getPeriodUsersCount(ChartPeriodsEnum $period): array;

    /**
     * @param ChartPeriodsEnum $period
     * @param string $statusCode
     * @return array
     */
    public function getPeriodOrdersCount(ChartPeriodsEnum $period, string $statusCode): array;

    /**
     * @param ChartPeriodsEnum $period
     * @param ReturnOrderStatusesEnum $status
     * @return array
     */
    public function getPeriodReturnOrdersCount(ChartPeriodsEnum $period, ReturnOrderStatusesEnum $status): array;

    /**
     * @param ChartPeriodsEnum $period
     * @param int $limit
     * @return Collection
     */
    public function getPeriodMostSaleProductsCount(ChartPeriodsEnum $period, int $limit = 5): Collection;
}
