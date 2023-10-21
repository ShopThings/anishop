<?php

namespace App\Services;

use App\Repositories\Contracts\WeightPostPriceRepositoryInterface;
use App\Services\Contracts\WeightPostPriceServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class WeightPostPriceService extends Service implements WeightPostPriceServiceInterface
{
    public function __construct(
        protected WeightPostPriceRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getPostPrices(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('weight_post_prices');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query->orWhereEqual([
                'min_weight',
                'max_weight',
                'post_price',
            ], $search);
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
            'min_weight' => $attributes['min_weight'],
            'max_weight' => $attributes['max_weight'],
            'post_price' => $attributes['post_price'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['min_weight'])) {
            $updateAttributes['min_weight'] = $attributes['min_weight'];
        }
        if (isset($attributes['max_weight'])) {
            $updateAttributes['max_weight'] = $attributes['max_weight'];
        }
        if (isset($attributes['post_price'])) {
            $updateAttributes['post_price'] = $attributes['post_price'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
