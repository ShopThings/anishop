<?php

namespace App\Repositories;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Http\Requests\Filters\OrderFilter;
use App\Models\GatewayPayment;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\OrderReserve;
use App\Models\ReturnOrderRequest;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository extends Repository implements OrderRepositoryInterface
{
    use RepositoryTrait;

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
                        'send_status_changer',
                        'orders',
                        'orders.payments',
                        'items',
                        'return_order'
                    ])
                    ->where('user_id', $uId);
            })
            ->when($search, function (Builder $query, string $search) use ($filter) {
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
    public function getOrdersCountWithBadges(): Collection
    {
        $orderQuery = $this->model->newQuery()
            ->selectRaw('count(order_details.*) as count')
            ->select('order_details.send_status_code')
            ->leftJoin(
                'order_badges',
                function ($join) {
                    $join->on('order_details.send_status_code', 'order_badges.code');
                }
            )
            ->groupBy('order_details.send_status_code');

        $badgeQuery = $this->model->newQuery()
            ->selectRaw('count(order_badges.*) as count')
            ->select(DB::raw('NULL as send_status_code'))
            ->rightJoin(
                'order_badges',
                function ($join) {
                    $join->on('order_details.send_status_code', 'order_badges.code');
                }
            )
            ->whereNull('order_details.id')
            ->groupBy('order_details.send_status_code');

        $unionQuery = $orderQuery->unionAll($badgeQuery);

        return $this->model->newQuery()
            ->select([
                'order_details.send_status_code',
                'order_details.send_status_title',
                'order_details.send_status_color_hex',
            ])
            ->joinSub(
                $unionQuery,
                'subquery',
                'order_details.send_status_code',
                '=',
                'subquery.send_status_code'
            )
            ->orderByDesc('order_details.ordered_at')
            ->get();
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
    public function isReturnOrderCancelable(ReturnOrderRequest $orderRequest): bool
    {
        return $orderRequest->status === ReturnOrderStatusesEnum::CHECKING->value;
    }

    /**
     * @inheritDoc
     */
    public function addPayments(array $chunks): bool
    {
        $insertCount = 0;

        foreach ($chunks as $chunk) {
            $model = $this->orderModel->newQuery()->create($chunk);

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
    public function returnOrderProductsToStock(int $orderId): bool
    {
        $productItems = $this->orderItemModel->newQuery()
            ->where('order_key_id', $orderId)
            ->get(['product_id', 'quantity']);
        $productRepo = $this->productRepository;

        $res = true;

        DB::transaction(function () use (&$res, $productItems, $productRepo) {
            $where = new WhereBuilder();
            foreach ($productItems as $item) {
                $where->reset()
                    ->whereEqual('product_id', $item['product_id']);
                $res = $res && $productRepo->updateWhere([
                        'stock_count' => 'stock_count+' . (+$item['quantity']),
                    ], $where->build());

                if (!$res) break;
            }

            if (!$res) {
                DB::rollBack();
            }
        });

        return $res;
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

        $result = DB::transaction(function () use ($detail, $reservedId) {
            $isOK = true;

            // Phase1:
            // -restore items quantity to stock
            $items = $detail->items();
            $items->each(function ($item) use (&$isOK) {
                $product = $item->product;

                if (!is_null($product)) {
                    $actualProduct = $product->items()->where('code', $item->product_code)->first();

                    if (!is_null($actualProduct)) {
                        $actualProduct->stock_count += $item->qunatity;
                        $isOK = $isOK && $actualProduct->save();
                    }
                }
            });

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
                ->where('payment_status', PaymentStatusesEnum::WAIT->value)
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

        return $result;
    }
}
