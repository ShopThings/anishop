<?php

namespace App\Services;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Models\OrderDetail;
use App\Models\ReturnOrderRequest;
use App\Models\ReturnOrderRequestItem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ReturnOrderRepositoryInterface;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnOrderService extends Service implements ReturnOrderServiceInterface
{
    public function __construct(
        protected ReturnOrderRepositoryInterface $repository,
        protected OrderRepositoryInterface       $orderRepository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getRequests(
        ?int   $userId = null,
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        return $this->repository
            ->newWith(['user', 'statusChangedBy'])
            ->getOrdersSearchFilterPaginated(userId: $userId, filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getUserOrdersCount($userId): int
    {
        $where = new WhereBuilder('return_order_requests');
        $where->whereEqual('user_id', $userId);

        return $this->repository->count($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getLatestUserRequests($userId, int $limit): Collection
    {
        $filter = new Filter();
        $filter->reset()
            ->setLimit($limit)
            ->setOrder([
                'requested_at' => 'desc',
                'id' => 'desc',
            ]);

        return collect($this->repository->getOrdersSearchFilterPaginated(
            userId: $userId,
            columns: ['code', 'order_code', 'status', 'requested_at'],
            filter: $filter
        )->items());
    }

    /**
     * @inheritDoc
     */
    public function getReturnableOrders($userId): Collection
    {
        return $this->orderRepository->getUserReturnableOrders(
            userId: $userId,
            columns: ['code', 'ordered_at']
        );
    }

    /**
     * @inheritDoc
     */
    public function canReturnOrder(OrderDetail $orderDetail): bool
    {
        return $this->orderRepository->isOrderReturnable($orderDetail);
    }

    /**
     * @inheritDoc
     */
    public function canCancelOrder(ReturnOrderRequest $orderRequest): bool
    {
        return $this->orderRepository->isReturnOrderCancelable($orderRequest);
    }

    /**
     * @inheritDoc
     */
    public function createUserRequest(int $userId, int $orderDetailId): ?Model
    {
        DB::beginTransaction();

        $request = $this->repository->create([
            'order_detail_id' => $orderDetailId,
            'user_id' => $userId,
            'code' => get_nanoid(),
            'status' => ReturnOrderStatusesEnum::CHECKING,
            'requested_at' => now(),
        ]);

        if ($request instanceof Model) {
            $items = [];
            foreach ($request->order->items as $k => $item) {
                $items[$k]['return_code'] = $request->code;
                $items[$k]['order_item_id'] = $item->id;
                $items[$k]['quantity'] = $item->quantity;
            }

            $inserted = $this->repository->updateOrCreateItems($request->code, $items);

            if ($inserted->count()) {
                DB::commit();
            } else {
                DB::rollBack();
                return null;
            }
        } else {
            DB::rollBack();
        }

        return $request;
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['not_accepted_description'])) {
            $updateAttributes['not_accepted_description'] = $attributes['not_accepted_description'];
        }
        if (isset($attributes['status'])) {
            $updateAttributes['status'] = $attributes['status'];
        }
        if (isset($attributes['seen_status'])) {
            $updateAttributes['seen_status'] = to_boolean($attributes['seen_status']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }

    /**
     * It'll return <b>false</b> if there is no valid item,
     * otherwise return <b>null</b> if it can't insert or
     * return inserted request model
     *
     * @inheritDoc
     */
    public function updateUserRequestByModel(
        int                $userId,
        ReturnOrderRequest $model,
        array              $attributes
    ): Model|bool|null
    {
        $items = $attributes['items'] ?? [];
        $items = $this->_refineItems($model, $items);

        // if there is no valid item
        if (!count($items)) return false;

        // don't need it to update the return order request
        unset($attributes['items']);

        DB::beginTransaction();

        $where = new WhereBuilder();
        $where
            ->whereEqual('id', $model->id)
            ->whereEqual('user_id', $userId);

        $res2 = $this->repository->updateWhere(data: $attributes, where: $where->build());
        $res = $this->repository->updateOrCreateItems($model->code, $items);

        if ($res->count() && $res2) {
            DB::commit();
            return $this->repository->find($model->id);
        } else {
            DB::rollBack();
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function cancelUserRequestById(int $userId, int $requestId, bool $permanent = false): bool
    {
        $where = new WhereBuilder();
        $where
            ->whereEqual('id', $requestId)
            ->whereEqual('user_id', $userId);

        return (bool)$this->repository->deleteWhere(
            where: $where->build(),
            permanent: $permanent
        );
    }

    /**
     * @inheritDoc
     */
    public function modifyItem(int $itemId, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['is_accepted']) && to_boolean($attributes['is_accepted'])) {
            $updateAttributes['accepted_at'] = now();
            $updateAttributes['accepted_by'] = Auth::user()?->id;
        }

        $res = $this->repository->modifyItem($itemId, $updateAttributes);

        if (!$res) return null;

        $where = new WhereBuilder();
        $where->whereEqual('id', $itemId);
        return $this->repository->getItemWhere(where: $where->build());
    }

    /**
     * @inheritDoc
     */
    public function getStatuses(): array
    {
        $statuses = ReturnOrderStatusesEnum::translationArray();
        $userStatuses = $this->getUserStatuses();

        return array_filter($statuses, function ($item, $key) use ($userStatuses) {
            return !in_array($key, array_keys($userStatuses));
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @inheritDoc
     */
    public function getUserStatuses(): array
    {
        $statuses = ReturnOrderStatusesEnum::getUserStatuses();

        $arrStatuses = [];
        foreach ($statuses as $status) {
            $arrStatuses[$status] = ReturnOrderStatusesEnum::getSimilarValuesFromString($status);
        }

        return $arrStatuses;
    }

    /**
     * @param ReturnOrderRequest $model
     * @param array $items
     * @return array
     */
    private function _refineItems(ReturnOrderRequest $model, array $items): array
    {
        $refined = [];
        $where = new WhereBuilder();

        foreach ($items as $k => $item) {
            /**
             * this is typehint to prevent show $orderItem other thing than a model
             * @var ReturnOrderRequestItem|null $orderItem
             */

            if (
                isset($item['id']) &&
                $orderItem = $this->repository->getItemWhere(
                        where: $where
                            ->whereEqual('id', $item['id'])
                            ->whereEqual('return_code', $model->code)
                            ->build(),
                        columns: ['order_item_id']
                    ) &&
                    $item['quantity'] >= 0
            ) {
                $refined[$k]['id'] = $item['id'];
                $refined[$k]['return_code'] = $model->code;
                $refined[$k]['order_item_id'] = $orderItem['order_item_id'];
                $refined[$k]['quantity'] = $item['quantity'];
            }

        }

        return $refined;
    }
}
