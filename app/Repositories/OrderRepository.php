<?php

namespace App\Repositories;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\ReturnOrderRequest;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderRepository extends Repository implements OrderRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(
        OrderDetail     $model,
        protected Order $orderModel
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
            ->when($userId, function (Builder $query, $uId) {
                $query->where('user_id', $uId);
            })
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->whereHas('user', function ($q) use ($search) {
                        $q->orWhereLike([
                            'username',
                            'first_name',
                            'last_name',
                            'national_code',
                        ], $search);
                    })
                    ->whereHas('orders', function ($q) use ($search) {
                        $q
                            ->when(PaymentTypesEnum::getSimilarValuesFromString($search), function ($q2, $types) {
                                $q2->whereIn('payment_method_type', $types);
                            })
                            ->when(PaymentStatusesEnum::getSimilarValuesFromString($search), function ($q2, $statuses) {
                                $q2->whereIn('payment_status', $statuses);
                            })
                            ->orWhereLike('payment_method_title', $search);
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

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
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
    public function isOrderCancelable(ReturnOrderRequest $orderRequest): bool
    {
        return $orderRequest->status === ReturnOrderStatusesEnum::CHECKING->value;
    }

    /**
     * @inheritDoc
     */
    public function updatePayment(int $orderId, array $attributes): bool|int
    {
        return $this->orderModel->newQuery()->find($orderId)->update($attributes);
    }
}
