<?php

namespace App\Services;

use App\Repositories\Contracts\ColorRepositoryInterface;
use App\Services\Contracts\ColorServiceInterface;
use App\Support\Model\CodeGeneratorHelper;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
    public function getColors(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('colors');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('name', $search);
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
