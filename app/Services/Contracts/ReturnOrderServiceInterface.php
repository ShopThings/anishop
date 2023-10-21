<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ReturnOrderServiceInterface extends ServiceInterface
{
    /**
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getOrders(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator;

    /**
     * @param array $items
     * @return Collection
     */
    public function updateItems(array $items): Collection;

    /**
     * @param int $itemId
     * @param array $attributes
     * @return Model|null
     */
    public function modifyItem(int $itemId, array $attributes): ?Model;
}
