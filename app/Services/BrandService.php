<?php

namespace App\Services;

use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Services\Contracts\BrandServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class BrandService extends Service implements BrandServiceInterface
{
    public function __construct(
        protected BrandRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getBrands(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('brands');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike([
                'latin_name',
                'escaped_name',
                'keywords',
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
            'name' => $attributes['name'],
            'latin_name' => $attributes['latin_name'],
            'escaped_name' => NumberConverter::toEnglish($attributes['name']),
            'image_id' => $attributes['image_id'],
            'keywords' => $attributes['keywords'],
            'show_in_slider' => to_boolean($attributes['show_in_slider']),
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
        if (isset($attributes['latin_name'])) {
            $updateAttributes['latin_name'] = $attributes['latin_name'];
        }
        if (isset($attributes['escaped_name'])) {
            $updateAttributes['escaped_name'] = $attributes['escaped_name'];
        }
        if (isset($attributes['image_id'])) {
            $updateAttributes['image_id'] = $attributes['image_id'];
        }
        if (isset($attributes['keywords'])) {
            $updateAttributes['keywords'] = $attributes['keywords'];
        }
        if (isset($attributes['show_in_slider'])) {
            $updateAttributes['show_in_slider'] = to_boolean($attributes['show_in_slider']);
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
