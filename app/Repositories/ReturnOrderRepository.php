<?php

namespace App\Repositories;

use App\Enums\Orders\ReturnOrderStatusesEnum;
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
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->whereHas('user', function ($q) use ($search) {
                        $q->orWhereLike([
                            'username',
                            'first_name',
                            'last_name',
                            'national_code',
                        ], $search);
                    })
                    ->whereHas('order', function ($q) use ($search) {
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
                    $model->update($item);
                }

                if ($isUpdated)
                    $modified->add($this->returnOrderRequestItemModel::first($item['id']));
            } else {
                $created = $this->returnOrderRequestItemModel::create($item + ['return_code' => $returnCode]);

                if ($created instanceof Model)
                    $modified->add($created);
            }
        }

        return $modified;
    }

    /**
     * @inheritDoc
     */
    public function modifyItem(int $itemId, array $attributes): bool|int
    {
        return $this->returnOrderRequestItemModel->newQuery()->find($itemId)->update($attributes);
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
}
