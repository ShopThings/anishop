<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface OrderServiceInterface extends ServiceInterface
{
    /**
     * @param int|null $userId
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getOrders(
        ?int    $userId = null,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator;

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
