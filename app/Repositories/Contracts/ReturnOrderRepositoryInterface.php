<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ReturnOrderRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int|null $userId
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getOrdersSearchFilterPaginated(
        ?int     $userId = null,
        array   $columns = ['*'],
        Filter $filter = null
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
