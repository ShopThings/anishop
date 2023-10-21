<?php

namespace App\Services;

use App\Repositories\Contracts\ReturnOrderRepositoryInterface;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use function App\Support\Helper\to_boolean;

class ReturnOrderService extends Service implements ReturnOrderServiceInterface
{
    public function __construct(
        protected ReturnOrderRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getOrders(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getOrdersSearchFilterPaginated(
            search: $searchText,
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
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
     * @inheritDoc
     */
    public function updateItems(array $items): Collection
    {
        $items = $this->_refineItems($items);
        return $this->repository->updateOrCreateItems($items);
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

        return $this->repository->getItem($itemId);
    }

    /**
     * @param array $items
     * @return array
     */
    private function _refineItems(array $items): array
    {
        $refined = [];
        foreach ($items as $item) {
            if (isset($item['id']) && $item['quantity'] > 0) {
                $refined[] = $item;
            }
        }
        return $refined;
    }
}
