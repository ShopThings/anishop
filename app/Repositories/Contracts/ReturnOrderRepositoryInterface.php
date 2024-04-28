<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Results\ReturnOrderToStockResult;
use App\Models\ReturnOrderRequest;
use App\Support\Filter;
use App\Support\WhereBuilder\GetterExpressionInterface;
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
        ?int  $userId = null,
        array $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param string $returnCode
     * @param array $items
     * @return Collection
     */
    public function updateOrCreateItems(string $returnCode, array $items): Collection;

    /**
     * @param ReturnOrderRequest $request
     * @return ReturnOrderToStockResult
     */
    public function returnItemsToStock(ReturnOrderRequest $request): ReturnOrderToStockResult;

    /**
     * @param int $itemId
     * @param array $attributes
     * @return bool|int
     */
    public function modifyItem(int $itemId, array $attributes): bool|int;

    /**
     * @param GetterExpressionInterface $where
     * @param array $columns
     * @return Model|null
     */
    public function getItemWhere(GetterExpressionInterface $where, array $columns = ['*']): ?Model;

    /**
     * @param ReturnOrderRequest $orderRequest
     * @return bool
     */
    public function isRequestCancelable(ReturnOrderRequest $orderRequest): bool;
}
