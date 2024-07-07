<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Results\ReturnOrderToStockResult;
use App\Models\OrderDetail;
use App\Models\ReturnOrderRequest;
use App\Models\ReturnOrderRequestItem;
use App\Repositories\Contracts\ReturnOrderRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReturnOrderRepository extends Repository implements ReturnOrderRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(
        ReturnOrderRequest               $model,
        protected ReturnOrderRequestItem $returnOrderRequestItemModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getOrdersSearchFilterPaginated(
        ?int   $userId = null,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query
            ->with('order')
            ->when($userId, function (Builder $query, $uId) {
                $query->where('user_id', $uId);
            })
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query
                    ->when($filter->getRelationSearch(), function ($q) use ($search) {
                        $q
                            ->orWhereHas('user', function ($q) use ($search) {
                                $q->where(function ($q) use ($search) {
                                    $q->orWhereLike([
                                        'username',
                                        'first_name',
                                        'last_name',
                                        'national_code',
                                    ], $search);
                                });
                            })
                            ->orWhereHas('order', function ($q) use ($search) {
                                $q->where(function ($q) use ($search) {
                                    $q->orWhereLike([
                                        'first_name',
                                        'last_name',
                                        'mobile',
                                        'province',
                                        'city',
                                        'receiver_name',
                                        'receiver_mobile',
                                        'send_status_title',
                                    ], $search);
                                });
                            });
                    })
                    ->when(ReturnOrderStatusesEnum::getSimilarValuesFromString($search), function ($q, $statuses) {
                        $q->whereIn('status', $statuses);
                    });
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreateItems(string $returnCode, array $items): Collection
    {
        $modified = collect();

        foreach ($items as $item) {
            if (isset($item['id'])) {
                $model = $this->returnOrderRequestItemModel->findOrFail($item['id']);
                $isUpdated = false;

                if ($model->return_code === $returnCode) {
                    $isUpdated = $model->update($item);
                }

                if ($isUpdated) {
                    $modified->add($this->returnOrderRequestItemModel::query()->find($item['id']));
                }
            } else {
                $created = $this->returnOrderRequestItemModel::create($item + ['return_code' => $returnCode]);

                if ($created instanceof Model) {
                    $modified->add($created);
                }
            }
        }

        return $modified;
    }

    /**
     * @inheritDoc
     */
    public function returnItemsToStock(ReturnOrderRequest $request): ReturnOrderToStockResult
    {
        $items = $request->returnOrderItems()
            ->whereNotNull('accepted_at')
            ->get();

        /**
         * @var OrderDetail $orderDetail
         */
        $orderDetail = $request->order;
        if ($orderDetail->is_product_returned_to_stock) {
            return ReturnOrderToStockResult::ALREADY_RETURNED;
        }

        DB::beginTransaction();

        $isOK = $orderDetail->update([
            'is_product_returned_to_stock' => DatabaseEnum::DB_YES,
        ]);

        if ($isOK) {
            /**
             * @var ReturnOrderRequestItem $returnItem
             */
            foreach ($items as $returnItem) {
                $isOK = $isOK && $returnItem->orderItem()->increment('quantity', $returnItem->quantity);
            }
        }

        if (!$isOK) {
            DB::rollBack();
            return ReturnOrderToStockResult::ERROR;
        }

        DB::commit();
        return ReturnOrderToStockResult::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    public function modifyItem(int $itemId, array $attributes): bool|int
    {
        return $this->returnOrderRequestItemModel->newQuery()->findOrFail($itemId)->update($attributes);
    }

    /**
     * @inheritDoc
     */
    public function getItemsWhere(GetterExpressionInterface $where, array $columns = ['*']): Collection
    {
        return $this->returnOrderRequestItemModel->newQuery()
            ->whereRaw($where->getStatement(), $where->getBindings())
            ->get($columns);
    }

    /**
     * @inheritDoc
     */
    public function getItemWhere(GetterExpressionInterface $where, array $columns = ['*']): ?Model
    {
        return $this->returnOrderRequestItemModel->newQuery()
            ->whereRaw($where->getStatement(), $where->getBindings())
            ->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function isRequestCancelable(ReturnOrderRequest $orderRequest): bool
    {
        return $orderRequest->status === ReturnOrderStatusesEnum::CHECKING->value;
    }
}
