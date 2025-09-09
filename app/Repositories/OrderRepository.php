<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Http\Requests\Filters\OrderFilter;
use App\Models\GatewayPayment;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\OrderReserve;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Support\Filter;
use App\Support\Helper\QBHelper;
use App\Support\QB\ItemActions\ComparisonItemAction;
use App\Support\QB\QueryItemActions;
use App\Support\QB\ReportQueryAppenderTrait;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository extends Repository implements OrderRepositoryInterface
{
    use RepositoryTrait,
        ReportQueryAppenderTrait;

    /**
     * @inheritDoc
     */
    protected function getMappedReportColumnToActualColumn(): array
    {
        return [
            'user' => 'user_id',
            'code' => 'code',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'national_code' => 'national_code',
            'mobile' => 'mobile',
            'province' => 'province',
            'city' => 'city',
            'postal_code' => 'postal_code',
            'address' => 'address',
            'receiver_name' => 'receiver_name',
            'receiver_mobile' => 'receiver_mobile',
            'description' => 'description',
            'coupon_code' => 'coupon_code',
            'coupon_price' => 'coupon_price',
            'shipping_price' => 'shipping_price',
            'discount_price' => 'discount_price',
            'final_price' => 'final_price',
            'total_price' => 'total_price',
            'send_method_title' => 'send_method_title',
            'send_status_title' => 'send_status_title',
            'send_status_changed_at' => 'send_status_changed_at',
            'is_needed_factor' => 'is_needed_factor',
            'is_returned' => 'is_product_returned_to_stock',
            'ordered_at' => 'ordered_at',
            //
            'payment_method_title' => [
                'column' => 'payment_method_title',
                'with' => 'orders',
            ],
            'payment_method_type' => [
                'column' => 'payment_method_type',
                'with' => 'orders',
            ],
            'payment_status' => [
                'column' => 'payment_status',
                'with' => 'orders',
            ],
            //
            'product_title' => [
                'column' => 'product_title',
                'with' => 'items',
            ],
            'color_name' => [
                'column' => 'color_name',
                'with' => 'items',
            ],
            'size' => [
                'column' => 'size',
                'with' => 'items',
            ],
            'guarantee' => [
                'column' => 'guarantee',
                'with' => 'items',
            ],
            'weight' => [
                'column' => 'weight',
                'with' => 'items',
            ],
            'quantity' => [
                'column' => 'quantity',
                'with' => 'items',
            ],
            'unit_price' => [
                'column' => 'unit_price',
                'with' => 'items',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getSpecialReportColumns(): array
    {
        return [
            'payment_method_title', 'payment_method_type', 'payment_status',
            'has_full_payment', 'product_title', 'color_name', 'size',
            'guarantee', 'weight', 'quantity', 'unit_price',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getIsMultipleColumns(): array
    {
        return [
            'payment_method_title', 'payment_method_type', 'payment_status',
            'product_title', 'color_name', 'size',
            'guarantee', 'weight', 'quantity', 'unit_price',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getHasReplacementColumns(): array
    {
        return [
            'payment_method_title', 'payment_method_type', 'payment_status',
            'product_title', 'color_name', 'size',
            'guarantee', 'weight', 'quantity', 'unit_price',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getComparisonColumns(): array
    {
        return [
            'payment_method_title', 'payment_method_type', 'payment_status',
            'product_title', 'color_name', 'size',
            'guarantee', 'weight', 'quantity', 'unit_price',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getBetweenColumns(): array
    {
        return [
            'payment_method_type', 'size', 'weight', 'quantity', 'unit_price',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getNullableColumns(): array
    {
        return [
            'payment_method_title', 'payment_method_type', 'payment_status',
            'product_title', 'color_name', 'size',
            'guarantee', 'weight', 'quantity', 'unit_price',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function specialReportQuery(Builder $query, array $item): Builder
    {
        $actions = new QueryItemActions([

            new ComparisonItemAction(function (
                array  $queryItem,
                string $columnName,
                string $operationStatement,
                       $value,
                string $condition
            ) use (&$query) {

                if ($columnName === 'has_full_payment') {

                    if ($queryItem['operator']['value'] == 'equal') {

                        $query->WithCompletePaidOrder($condition);

                    } elseif ($queryItem['operator']['value'] == 'notEqual') {

                        $query->WithoutCompletePaidOrder($condition);

                    }

                }

            }),

        ]);

        QBHelper::queryItemAction($actions, $item);

        return $query;
    }

    public function __construct(
        OrderDetail                          $model,
        protected Order                      $orderModel,
        protected OrderItem                  $orderItemModel,
        protected ProductRepositoryInterface $productRepository,
        protected OrderReserve               $orderReserveModel,
        protected GatewayPayment             $gatewayPaymentModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getOrdersSearchFilterPaginated(
        ?int   $userId = null,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query
            ->with('user')
            ->when($userId, function (Builder $query, $uId) {
                $query
                    ->with([
                        'sendStatusChanger',
                        'orders',
                        'orders.payments',
                        'items',
                        'returnOrder'
                    ])
                    ->where('user_id', $uId);
            })
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query->where(function ($query) use ($search, $filter) {
                    $query
                        ->when($filter->getRelationSearch(), function ($q) use ($search) {
                            $q
                                ->orWhereHas('user', function ($q) use ($search) {
                                    $q->where(function ($q) use ($search) {
                                        $q->orWhereLike([
                                            'username',
                                            'first_name',
                                            'last_name',
                                            'national_code',
                                        ], $search);
                                    });
                                })
                                ->orWhereHas('orders', function ($q) use ($search) {
                                    $q->where(function ($q) use ($search) {
                                        $q
                                            ->when(PaymentTypesEnum::getSimilarValuesFromString($search), function ($q2, $types) {
                                                $q2->orWhereIn('payment_method_type', $types);
                                            })
                                            ->when(PaymentStatusesEnum::getSimilarValuesFromString($search), function ($q2, $statuses) {
                                                $q2->orWhereIn('payment_status', $statuses);
                                            })
                                            ->orWhereLike([
                                                'payment_method_title',
                                            ], $search);
                                    });
                                });
                        })
                        ->orWhereLike([
                            'code',
                            'first_name',
                            'last_name',
                            'mobile',
                            'province',
                            'city',
                            'receiver_name',
                            'receiver_mobile',
                            'send_status_title',
                        ], $search);
                });
            });

        //

        if ($filter instanceof OrderFilter) {
            $badgeCode = $filter->getBadgeCode();

            $query->when($badgeCode, function ($q, $badgeCode) {
                if (is_array($badgeCode)) {
                    $q->whereIn('send_status_code', $badgeCode);
                } else {
                    $q->where('send_status_code', $badgeCode);
                }
            });
        }

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getOrdersFilterPaginatedForReport(
        Filter $filter = null,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator
    {
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query->with('user');

        if (!empty($reportQuery)) {
            $query = $this->addToEloquentBuilder($query, $reportQuery);
        }

        return $this->_paginateWithOrder(query: $query, limit: $limit, page: $page, order: $order);
    }

    /**
     * @inheritDoc
     */
    public function getOrdersCountWithBadges(): Collection
    {
        $orderQuery = $this->model->newQuery()
            ->select([
                DB::raw('count(order_details.id) as count'),
                'order_details.send_status_code',
            ])
            ->leftJoin(
                'order_badges',
                function ($join) {
                    $join->on('order_details.send_status_code', 'order_badges.code');
                }
            )
            ->groupBy('order_details.send_status_code');

        $badgeQuery = $this->model->newQuery()
            ->select([
                DB::raw('count(order_details.send_status_code) as count'),
                DB::raw('order_badges.code as send_status_code'),
            ])
            ->rightJoin(
                'order_badges',
                function ($join) {
                    $join->on('order_details.send_status_code', 'order_badges.code');
                }
            )
            ->whereNull('order_details.id')
            ->groupBy('order_badges.code');

        $unionQuery = $orderQuery->unionAll($badgeQuery);

        //

        $mainQuery = $this->model->newQuery()
            ->select([
                'subquery.count',
                'order_details.send_status_code',
                'order_details.send_status_title',
                'order_details.send_status_color_hex',
            ])
            ->joinSub(
                $unionQuery,
                'subquery',
                'order_details.send_status_code',
                '=',
                'subquery.send_status_code',
                'right'
            )
            ->whereNotNull('order_details.id')
            ->orderByDesc('order_details.ordered_at')
            ->groupBy([
                'subquery.count',
                'order_details.send_status_code',
                'order_details.send_status_title',
                'order_details.send_status_color_hex'
            ]);

        $secondQuery = $this->model->newQuery()
            ->select([
                'subquery.count',
                'order_badges.code as send_status_code',
                'order_badges.title as send_status_title',
                'order_badges.color_hex as send_status_color_hex',
            ])
            ->rightJoin('order_badges', 'order_badges.code', '=', 'order_details.send_status_code')
            ->joinSub(
                $unionQuery,
                'subquery',
                'order_badges.code',
                '=',
                'subquery.send_status_code',
                'right'
            )
            ->whereNull('order_details.id')
            ->orderByDesc('order_details.ordered_at')
            ->groupBy([
                'subquery.count',
                'order_badges.code',
                'order_badges.title',
                'order_badges.color_hex'
            ]);

        return $mainQuery->unionAll($secondQuery)->get();
    }

    /**
     * @inheritDoc
     */
    public function getPayment(int $orderId): ?Model
    {
        return $this->orderModel->newQuery()->find($orderId);
    }

    /**
     * @inheritDoc
     */
    public function getUserReturnableOrders(int $userId, array $columns = ['*']): Collection
    {
        return $this->model->newQuery()
            ->withAnyPaidOrder()
            ->where('user_id', $userId)
            ->where('ordered_at', '>=', now()->subWeek())
            ->where('send_status_can_return_order', DatabaseEnum::DB_YES)
            ->get($columns);
    }

    /**
     * @inheritDoc
     */
    public function isOrderReturnable(OrderDetail $orderDetail): bool
    {
        return $orderDetail->hasCompletePaid() &&
            $orderDetail->ordered_at >= now()->subWeek() &&
            $orderDetail->send_status_can_return_order;
    }

    /**
     * @inheritDoc
     */
    public function addPayments(array $chunks): bool
    {
        $insertCount = 0;

        foreach ($chunks as $chunk) {
            $model = $this->orderModel::create($chunk);

            if ($model instanceof Model) {
                $insertCount += 1;
            }
        }

        return $insertCount === count($chunks);
    }

    /**
     * @inheritDoc
     */
    public function updatePayment(int $orderId, array $attributes): bool
    {
        return !!$this->orderModel->newQuery()->find($orderId)?->update($attributes);
    }

    /**
     * @inheritDoc
     */
    public function canReduceItemsFromStockFor(int $orderId): bool
    {
        $detail = $this->model->newQuery()
            ->with('items.product.items')
            ->where('id', $orderId)
            ->has('items.product.items')
            ->first();

        if (empty($detail)) return false;

        $res = false;

        $detail->items?->each(function ($item) use (&$res) {
            $realItem = $item->product()->items()->where('code', $item->product_code)->first();

            if (empty($realItem) || $realItem->stock_count - $item->quantity < 0) {
                $res = false;
                return false;
            }

            $res = true;
            return true;
        });

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function returnOrderProductsToStock(int $orderId): bool
    {
        $productItems = $this->orderItemModel->newQuery()
            ->where('order_key_id', $orderId)
            ->get(['product_id', 'quantity']);

        return $this->updateProductsStock($productItems);
    }

    /**
     * @inheritDoc
     */
    public function reduceOrderProductsFromStock(array $items): bool
    {
        return $this->updateProductsStock($items, false);
    }

    /**
     * @inheritDoc
     */
    public function addItemsToOrder(array $items): bool
    {
        $insertCount = 0;

        foreach ($items as $item) {
            $model = $this->orderItemModel->newQuery()->create($item);

            if ($model instanceof Model) {
                $insertCount += 1;
            }
        }

        return $insertCount === count($items);
    }

    /**
     * @inheritDoc
     */
    public function createGatewayPayment(array $attributes): ?Model
    {
        return $this->gatewayPaymentModel::create($attributes);
    }

    /**
     * @inheritDoc
     */
    public function createReserveOrder(array $attributes): ?Model
    {
        return $this->orderReserveModel::create($attributes);
    }

    /**
     * @inheritDoc
     */
    public function rollbackReservedOrder(string $code, ?int $reservedId): bool
    {
        $detail = $this->model->newQuery()
            ->where('code', $code)
            ->first();

        if (is_null($detail)) return false;

        return DB::transaction(function () use ($detail, $reservedId) {
            // Phase1:
            // -restore items quantity to stock
            $isOK = $this->returnOrderProductsToStock($detail->id);

            // check if first phase is done
            if (!$isOK) {
                DB::rollBack();
                return false;
            }

            // Phase2:
            // -remove reserved record(s)
            $reservedRecords = collect();
            if (is_null($reservedId)) {
                $reservedRecords = $detail->reservedOrders;
            } else {
                $reservedRecords->add($this->orderReserveModel->newQuery()->find($reservedId));
            }

            $reservedRecords->each(function ($record) use (&$isOK) {
                $isOK = $isOK && !!$record->delete();
            });

            // check if second phase is done
            if (!$isOK) {
                DB::rollBack();
                return false;
            }

            // Phase3:
            // -make returned to true in order detail
            $detail->is_product_returned_to_stock = true;
            $isOK = $isOK && $detail->save();

            // check if third phase is done
            if (!$isOK) {
                DB::rollBack();
                return false;
            }

            // Phase4:
            // -make waited payments to not paid
            $waitedPayments = $this->orderModel->newQuery()
                ->where('key_id', $detail->id)
                ->where('payment_status', PaymentStatusesEnum::PENDING->value)
                ->get();

            /**
             * @var Order $item
             */
            foreach ($waitedPayments as $item) {
                $item->payment_status = PaymentStatusesEnum::NOT_PAID->value;
                $isOK = $isOK && $item->save();

                if (!$isOK) {
                    break;
                }
            }

            // -make paid payments to unwanted paid,
            //  that means paid money needs to return to user
            $paidPayments = $this->orderModel->newQuery()
                ->where('key_id', $detail->id)
                ->where('payment_status', PaymentStatusesEnum::SUCCESS->value)
                ->get();

            /**
             * @var Order $item
             */
            foreach ($paidPayments as $item) {
                $item->payment_status = PaymentStatusesEnum::UNWANTED_SUCCESS->value;
                $isOK = $isOK && $item->save();

                if (!$isOK) {
                    break;
                }
            }

            // check if forth phase is done
            if (!$isOK) {
                DB::rollBack();
                return false;
            }

            //-----------------------------------------------------
            // 📍[NOTE]
            // -there is no need to revert coupon usage,
            //  because coupons have different way of calculation
            //-----------------------------------------------------

            // If all phases are done, let's return true
            // It'll be committed automatically
            return true;
        });
    }

    /**
     * @param array|Collection $items
     * @param bool $increase
     * @return bool
     */
    private function updateProductsStock(array|Collection $items, bool $increase = true): bool
    {
        $res = true;

        DB::transaction(function () use (&$res, $items, $increase) {
            foreach ($items as $item) {
                $qty = abs(intval($item['quantity']));

                $res = $res && $this->productRepository->updateProductStockFor(
                        $item['product_id'],
                        !$increase ? -$qty : $qty
                    );

                if (!$res) break;
            }

            if (!$res) {
                DB::rollBack();
                return false;
            }
            return true;
        });

        return $res;
    }
}
