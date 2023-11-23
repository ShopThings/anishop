<?php

namespace App\Services;

use App\Enums\Products\ProductAttributeTypesEnum;
use App\Repositories\Contracts\ProductAttributeRepositoryInterface;
use App\Services\Contracts\ProductAttributeServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeService extends Service implements ProductAttributeServiceInterface
{
    public function __construct(
        protected ProductAttributeRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('product_attributes');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query
                ->when(
                    ProductAttributeTypesEnum::getSimilarValuesFromString($search),
                    function (WhereBuilderInterface $q, array $types) use ($search) {
                        $q->orWhereIn('type', $types);
                    }
                )
                ->orWhereLike('title', $search);
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
            'title' => $attributes['title'],
            'type' => $attributes['type'],
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
        if (isset($attributes['type'])) {
            $updateAttributes['type'] = $attributes['type'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
