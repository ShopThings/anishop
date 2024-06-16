<?php

namespace App\Repositories\Contracts;

use App\Enums\Charts\ChartPeriodsEnum;
use App\Enums\Orders\ReturnOrderStatusesEnum;
use Illuminate\Support\Collection;

interface PeriodicRepositoryInterface
{
    /**
     * @param ChartPeriodsEnum|null $period
     * @return int
     */
    public function getPeriodSale(?ChartPeriodsEnum $period = null): int;

    /**
     * Returns an array like:
     * <code>
     * [
     *   'label' => array,
     *   'data' => array,
     * ]
     * </code>
     *
     * @param ChartPeriodsEnum $period
     * @return array
     */
    public function getPeriodUsersCount(ChartPeriodsEnum $period): array;

    /**
     * Returns an array like:
     * <code>
     * [
     *   'label' => array,
     *   'data' => array,
     *   'send_status_title' => string,
     *   'send_status_color_hex' => string,
     * ]
     * </code>
     *
     * @param ChartPeriodsEnum $period
     * @param string $statusCode
     * @return array
     */
    public function getPeriodOrdersCount(ChartPeriodsEnum $period, string $statusCode): array;

    /**
     * Returns an array like:
     * <code>
     * [
     *   'label' => array,
     *   'data' => array,
     * ]
     * </code>
     *
     * @param ChartPeriodsEnum $period
     * @param ReturnOrderStatusesEnum $status
     * @return array
     */
    public function getPeriodReturnOrdersCount(ChartPeriodsEnum $period, ReturnOrderStatusesEnum $status): array;

    /**
     * Returns an array like:
     * <code>
     * [
     *   'label' => array,
     *   'data' => array,
     * ]
     * </code>
     *
     * @param ChartPeriodsEnum $period
     * @param int $limit
     * @return Collection
     */
    public function getPeriodMostSaleProductsCount(ChartPeriodsEnum $period, int $limit = 5): Collection;
}
