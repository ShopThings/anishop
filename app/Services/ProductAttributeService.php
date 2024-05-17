<?php

namespace App\Services;

use App\Enums\Products\ProductAttributeTypesEnum;
use App\Repositories\Contracts\ProductAttributeRepositoryInterface;
use App\Services\Contracts\ProductAttributeServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
    public function getAttributes(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('product_attributes');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
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
            where: $where->build(),
            limit: $filter->getLimit(),
            page: $filter->getPage(),
            order: $filter->getOrder()
        );
    }

    /**
     * @inheritDoc
     */
    public function getAttributesCount(): int
    {
        return $this->repository->count();
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
