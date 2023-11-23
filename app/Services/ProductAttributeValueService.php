<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeValueRepositoryInterface;
use App\Services\Contracts\ProductAttributeValueServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValueService extends Service implements ProductAttributeValueServiceInterface
{
    public function __construct(
        protected ProductAttributeValueRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getValues(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('product_attribute_values');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('attribute_value', $search);
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
            'product_attribute_id' => $attributes['product_attribute'],
            'attribute_value' => $attributes['attribute_value'],
            'priority' => $attributes['priority'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['product_attribute'])) {
            $updateAttributes['product_attribute_id'] = $attributes['product_attribute'];
        }
        if (isset($attributes['attribute_value'])) {
            $updateAttributes['attribute_value'] = $attributes['attribute_value'];
        }
        if (isset($attributes['priority'])) {
            $updateAttributes['priority'] = $attributes['priority'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
