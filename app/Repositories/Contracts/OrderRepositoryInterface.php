<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Models\OrderDetail;
use App\Models\ReturnOrderRequest;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int|null $userId
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getOrdersSearchFilterPaginated(
        ?int  $userId = null,
        array $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param int $orderId
     * @return Model|null
     */
    public function getPayment(int $orderId): ?Model;

    /**
     * @param int $userId
     * @param array $columns
     * @return Collection
     */
    public function getUserReturnableOrders(int $userId, array $columns = ['*']): Collection;

    /**
     * @param OrderDetail $orderDetail
     * @return bool
     */
    public function isOrderReturnable(OrderDetail $orderDetail): bool;

    /**
     * @param OrderDetail $orderDetail
     * @return bool
     */
    public function isReturnOrderCancelable(ReturnOrderRequest $orderRequest): bool;

    /**
     * @param int $orderId
     * @param array $attributes
     * @return bool|int
     */
    public function updatePayment(int $orderId, array $attributes): bool|int;

    /**
     * @param int $orderId
     * @return bool
     */
    public function returnOrderProductsToStock(int $orderId): bool;
}
