<?php

namespace App\Repositories;

use App\Enums\Charts\ChartPeriodsEnum;
use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Models\OrderDetail;
use App\Models\ReturnOrderRequest;
use App\Models\User;
use App\Repositories\Contracts\PeriodicRepositoryInterface;
use App\Traits\RepositoryFilterableByDatesTrait;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PeriodicRepository implements PeriodicRepositoryInterface
{
    use RepositoryFilterableByDatesTrait;

    public function __construct(
        protected OrderDetail        $orderDetailModel,
        protected User               $userModel,
        protected ReturnOrderRequest $returnOrderRequestModel
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getPeriodSale(?ChartPeriodsEnum $period = null): int
    {
        $query = $this->orderDetailModel->newQuery()->withCompletePaidOrder();

        if (!is_null($period)) {
            match ($period->value) {
                ChartPeriodsEnum::WEEKLY->value => $query->thisWeek('ordered_at'),
                ChartPeriodsEnum::MONTHLY->value => $query->thisMonth('ordered_at'),
                ChartPeriodsEnum::MONTHS_3->value => $query->lastMonths(3, 'ordered_at'),
                ChartPeriodsEnum::MONTHS_6->value => $query->lastMonths(6, 'ordered_at'),
                ChartPeriodsEnum::YEARLY->value => $query->thisYear('ordered_at'),
                default => $query->today('ordered_at'),
            };
        }

        return $query->sum('final_price');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getPeriodUsersCount(ChartPeriodsEnum $period): array
    {
        $filteredData = $this->getPeriodicDataFrom(
            $this->userModel->newQuery()->verified(),
            $period,
            'verified_at'
        );

        $labels = [];
        $data = [];
        foreach ($filteredData as $item) {
            $labels[] = $item['label'];
            $data[] = $item['data'];
        }

        return compact('labels', 'data');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getPeriodOrdersCount(ChartPeriodsEnum $period, string $statusCode): array
    {
        $filteredData = $this->getPeriodicDataFrom(
            $this->orderDetailModel->newQuery()->where('send_status_code', $statusCode),
            $period,
            'ordered_at'
        );

        $labels = [];
        $data = [];
        foreach ($filteredData as $item) {
            $labels[] = $item['label'];
            $data[] = $item['data'];
        }

        $lastOrder = $this->orderDetailModel->newQuery()
            ->where('send_status_code', $statusCode)
            ->orderByDesc('ordered_at')
            ->first();

        $send_status_title = $lastOrder?->send_status_title;
        $send_status_color_hex = $lastOrder?->send_status_color_hex;

        return compact('labels', 'data', 'send_status_title', 'send_status_color_hex');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getPeriodReturnOrdersCount(ChartPeriodsEnum $period, ReturnOrderStatusesEnum $status): array
    {
        $filteredData = $this->getPeriodicDataFrom(
            $this->returnOrderRequestModel->newQuery()->where('status', $status),
            $period,
            'requested_at'
        );

        $labels = [];
        $data = [];
        foreach ($filteredData as $item) {
            $labels[] = $item['label'];
            $data[] = $item['data'];
        }

        return compact('labels', 'data');
    }

    /**
     * @inheritDoc
     */
    public function getPeriodMostSaleProductsCount(ChartPeriodsEnum $period, int $limit = 5): Collection
    {
        $query = $this->orderDetailModel->newQuery()->limit($limit)->withCompletePaidOrder();

        $query
            ->select([
                'subquery.quantity',
                'order_items.product_title',
                'order_items.unit_name',
                'order_items.product_id',
            ])
            ->distinct()
            ->join('order_items', 'order_items.order_key_id', '=', 'order_details.id')
            ->joinSub(
                DB::table('order_items')
                    ->select([
                        'order_items.product_id',
                        'order_items.order_key_id',
                        DB::raw('SUM(order_items.quantity) as quantity')
                    ])
                    ->groupBy(['order_items.product_id', 'order_items.order_key_id'])
                    ->orderByDesc('quantity'),
                'subquery',
                function ($join) {
                    $join->on('order_items.product_id', '=', 'subquery.product_id')
                        ->on('subquery.order_key_id', '=', 'order_details.id');
                }
            )
            ->orderByDesc('subquery.quantity');

        match ($period->value) {
            ChartPeriodsEnum::MONTHLY->value => $query->thisMonth('ordered_at'),
            ChartPeriodsEnum::MONTHS_3->value => $query->lastMonths(3, 'ordered_at'),
            ChartPeriodsEnum::MONTHS_6->value => $query->lastMonths(6, 'ordered_at'),
            ChartPeriodsEnum::YEARLY->value => $query->thisYear('ordered_at'),
            default => $query->thisWeek('ordered_at'),
        };

        return $query->get();
    }
}
