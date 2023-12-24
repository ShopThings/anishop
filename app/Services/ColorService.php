<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Repositories\Contracts\ColorRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\ColorServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class ColorService extends Service implements ColorServiceInterface
{
    public function __construct(
        protected ColorRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getColors(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('colors');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('name', $search);
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
            'name' => $attributes['name'],
            'hex' => $attributes['hex'],
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

        if (isset($attributes['name'])) {
            $updateAttributes['name'] = $attributes['name'];
        }
        if (isset($attributes['hex'])) {
            $updateAttributes['hex'] = $attributes['hex'];
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
