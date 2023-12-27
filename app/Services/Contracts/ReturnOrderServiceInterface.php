<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Models\OrderDetail;
use App\Models\ReturnOrderRequest;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ReturnOrderServiceInterface extends ServiceInterface
{
    /**
     * @param int|null $userId
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getRequests(?int $userId = null, Filter $filter = null): Collection|LengthAwarePaginator;

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
    public function getLatestUserRequests($userId, int $limit): Collection;

    /**
     * @param $userId
     * @return Collection
     */
    public function getReturnableOrders($userId): Collection;

    /**
     * @param OrderDetail $orderDetail
     * @return bool
     */
    public function canReturnOrder(OrderDetail $orderDetail): bool;

    /**
     * @param ReturnOrderRequest $orderRequest
     * @return bool
     */
    public function canCancelOrder(ReturnOrderRequest $orderRequest): bool;

    /**
     * @param int $userId
     * @param int $orderDetailId
     * @return Model|null
     */
    public function createUserRequest(int $userId, int $orderDetailId): ?Model;

    /**
     * @return Model|null
     */
    public function updateUserRequestByModel(
        int                $userId,
        ReturnOrderRequest $model,
        array              $attributes
    ): Model|bool|null;

    /**
     * @param int $userId
     * @param int $requestId
     * @param bool $permanent
     * @return bool
     */
    public function cancelUserRequestById(int $userId, int $requestId, bool $permanent = false): bool;

    /**
     * @param int $itemId
     * @param array $attributes
     * @return Model|null
     */
    public function modifyItem(int $itemId, array $attributes): ?Model;
}
