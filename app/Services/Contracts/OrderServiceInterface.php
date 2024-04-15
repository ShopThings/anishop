<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Models\User;
use App\Support\Cart\Cart;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface OrderServiceInterface extends ServiceInterface
{
    /**
     * @param string $code
     * @return Model|null
     */
    public function getByCode(string $code): ?Model;

    /**
     * @param int $userId
     * @param string $code
     * @return Model|null
     */
    public function getUserOrderByCode(int $userId, string $code): ?Model;

    /**
     * @param int|null $userId
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getOrders(?int $userId = null, Filter $filter = null): Collection|LengthAwarePaginator;

    /**
     * @return Collection
     */
    public function getOrdersCountWithBadges(): Collection;

    /**
     * @return array
     */
    public function getPaymentStatuses(): array;

    /**
     * @return Collection
     */
    public function getSendStatuses(): Collection;

    /**
     * @param $userId
     * @return int
     */
    public function getUserOrdersCount($userId): int;

    /**
     * @param $userId
     * @param int $limit
     * @return Collection
     */
    public function getUserLatestOrders($userId, int $limit): Collection;

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserUnpaidOrderPayments(User $user): Collection;

    /**
     * It'll store all required things and returns stored order details model
     * or <strong>null</strong> in case of error
     *
     * @param User $user
     * @param array $orderInfo
     * @param Cart $cart
     * @return Model|null
     */
    public function placeOrder(User $user, array $orderInfo, Cart $cart): ?Model;

    /**
     * @param string $code
     * @param int|null $reservedId
     * @return bool
     */
    public function rollbackReservedOrder(string $code, ?int $reservedId): bool;

    /**
     * @param $code
     * @param array $attributes
     * @param bool $silence
     * @return Model|null
     */
    public function updateByCode($code, array $attributes, bool $silence = false): ?Model;

    /**
     * @param int $orderId
     * @param array $attributes
     * @param bool $saveChanger
     * @return Model|null
     */
    public function updatePayment(int $orderId, array $attributes, bool $saveChanger = true): ?Model;
}
