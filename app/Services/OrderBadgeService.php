<?php

namespace App\Services;

use App\Repositories\Contracts\OrderBadgeRepositoryInterface;
use App\Services\Contracts\OrderBadgeServiceInterface;
use App\Support\Model\CodeGeneratorHelper;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class OrderBadgeService extends Service implements OrderBadgeServiceInterface
{
    public function __construct(
        protected OrderBadgeRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getBadges(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('order_badges');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('title', $search);
        });

        return $this->repository->paginate(
            where: $where->build(), page: $page, limit: $limit, order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'code' => CodeGeneratorHelper::orderBadgeCode(),
            'title' => $attributes['title'],
            'color_hex' => $attributes['color_hex'],
            'should_return_order_product' => to_boolean($attributes['should_return_order_product']),
            'is_published' => to_boolean($attributes['is_published']),
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['title'])) {
            $updateAttributes['title'] = $attributes['title'];
        }
        if (isset($attributes['color_hex'])) {
            $updateAttributes['color_hex'] = $attributes['color_hex'];
        }
        if (isset($attributes['is_starting_badge'])) {
            $updateAttributes['is_starting_badge'] = to_boolean($attributes['is_starting_badge']);
        }
        if (isset($attributes['should_return_order_product'])) {
            $updateAttributes['should_return_order_product'] = to_boolean($attributes['should_return_order_product']);
        }
        if (isset($attributes['is_title_editable'])) {
            $updateAttributes['is_title_editable'] = to_boolean($attributes['is_title_editable']);
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }
        if (isset($attributes['is_deletable'])) {
            $updateAttributes['is_deletable'] = to_boolean($attributes['is_deletable']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
