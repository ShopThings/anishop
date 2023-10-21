<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int|null $userId
     * @param array $columns
     * @param string|null $search
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getOrdersSearchFilterPaginated(
        ?int     $userId = null,
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator;

    /**
     * @param int $orderId
     * @param array $attributes
     * @return bool|int
     */
    public function updatePayment(int $orderId, array $attributes): bool|int;

    /**
     * @param int $orderId
     * @return Model|null
     */
    public function getPayment(int $orderId): ?Model;
}
