<?php

namespace App\Services;

use App\Repositories\Contracts\WeightPostPriceRepositoryInterface;
use App\Services\Contracts\WeightPostPriceServiceInterface;
use App\Support\Filter;
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
    public function getPostPrices(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('weight_post_prices');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereEqual([
                'min_weight',
                'max_weight',
                'post_price',
            ], $search);
        });

        return $this->repository->paginate(
            where: $where->build(),
            limit: $filter->getLimit(),
            page: $filter->getPage(),
            order: $filter->getOrder()
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
