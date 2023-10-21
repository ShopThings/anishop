<?php

namespace App\Repositories;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
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
        ?int    $userId = null,
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query
            ->when($userId, function (Builder $query, $uId) {
                $query->where('id', $uId);
            })
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->withWhereHas('user', function ($q) use ($search) {
                        $q->orWhereLike([
                            'username',
                            'first_name',
                            'last_name',
                            'national_code',
                        ], $search);
                    })
                    ->withWhereHas('orders', function ($q) use ($search) {
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
    public function updatePayment(int $orderId, array $attributes): bool|int
    {
        return $this->orderModel->newQuery()->find($orderId)->update($attributes);
    }

    /**
     * @inheritDoc
     */
    public function getPayment(int $orderId): ?Model
    {
        return $this->orderModel->newQuery()->find($orderId);
    }
}
