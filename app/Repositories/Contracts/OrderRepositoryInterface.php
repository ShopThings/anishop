<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Models\OrderDetail;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
     * @param Filter|null $filter
     * @param array|null $reportQuery
     * @return Collection|LengthAwarePaginator
     */
    public function getOrdersFilterPaginatedForReport(
        Filter $filter = null,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator;

    /**
     * @return Collection
     */
    public function getOrdersCountWithBadges(): Collection;

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
     * It'll receive an array of payments (that is array too) and
     * return a boolean that indicates if all the payments are created
     *
     * @param array<array> $chunks
     * @return bool
     */
    public function addPayments(array $chunks): bool;

    /**
     * @param int $orderId
     * @param array $attributes
     * @return bool
     */
    public function updatePayment(int $orderId, array $attributes): bool;

    /**
     * @param int $orderId
     * @return bool
     */
    public function returnOrderProductsToStock(int $orderId): bool;

    /**
     * It'll receive an array of items (that is array too) and
     * return a boolean that indicates if all the items are created
     *
     * @param array<array> $items
     * @return bool
     */
    public function addItemsToOrder(array $items): bool;

    /**
     * @param array $attributes
     * @return Model|null
     */
    public function createGatewayPayment(array $attributes): ?Model;

    /**
     * @param array $attributes
     * @return Model|null
     */
    public function createReserveOrder(array $attributes): ?Model;

    /**
     * @param string $code
     * @param int|null $reservedId
     * @return bool
     */
    public function rollbackReservedOrder(string $code, ?int $reservedId): bool;
}
