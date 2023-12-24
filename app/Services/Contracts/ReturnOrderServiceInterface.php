<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
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
    public function getOrders(?int $userId = null, Filter $filter = null): Collection|LengthAwarePaginator;

    /**
     * @param $userId
     * @return int
     */
    public function getUserOrdersCount($userId): int;

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
