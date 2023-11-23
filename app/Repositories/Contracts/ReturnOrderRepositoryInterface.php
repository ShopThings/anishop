<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ReturnOrderRepositoryInterface extends RepositoryInterface
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
     * @param array $items
     * @return Model|Collection
     */
    public function updateOrCreateItems(array $items): Collection;

    /**
     * @param int $itemId
     * @param array $attributes
     * @return bool|int
     */
    public function modifyItem(int $itemId, array $attributes): bool|int;

    /**
     * @param int $itemId
     * @return Model|null
     */
    public function getItem(int $itemId): ?Model;
}
