<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface OrderServiceInterface extends ServiceInterface
{
    /**
     * @param int|null $userId
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getOrders(?int $userId = null, Filter $filter = null): Collection|LengthAwarePaginator;

    /**
     * @param int $orderId
     * @param array $attributes
     * @return Model|null
     */
    public function updatePayment(int $orderId, array $attributes): ?Model;

    /**
     * @return array
     */
    public function getPaymentStatuses(): array;

    /**
     * @return Collection
     */
    public function getSendStatuses(): Collection;
}
