<?php

namespace App\Services;

use App\Repositories\Contracts\SendMethodRepositoryInterface;
use App\Services\Contracts\SendMethodServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\Traits\ImageFieldTrait;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class SendMethodService extends Service implements SendMethodServiceInterface
{
    use ImageFieldTrait;

    public function __construct(
        protected SendMethodRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getMethods(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('send_methods');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('title', $search);
        });

        return $this->repository
            ->newWith(['image', 'creator', 'updater', 'deleter'])
            ->paginate(
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
        $attributes['image'] = $this->getImageId($attributes['image'] ?? null);

        $attrs = [
            'code' => get_nanoid(),
            'title' => $attributes['title'],
            'description' => $attributes['description'] ?? '',
            'image_id' => $attributes['image'],
            'price' => $attributes['price'] ?? 0,
            'priority' => abs($attributes['priority'] ?? 0),
            'determine_price_by_shop_location' => to_boolean($attributes['determine_price_by_shop_location']),
            'only_for_shop_location' => to_boolean($attributes['only_for_shop_location']),
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
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes['description'];
        }
        if (isset($attributes['image'])) {
            $attributes['image'] = $this->getImageId($attributes['image'] ?? null);
            $updateAttributes['image_id'] = $attributes['image'];
        }
        if (isset($attributes['price'])) {
            $updateAttributes['price'] = $attributes['price'];
        }
        if (isset($attributes['priority'])) {
            $updateAttributes['priority'] = abs($attributes['priority']);
        }
        if (isset($attributes['determine_price_by_shop_location'])) {
            $updateAttributes['determine_price_by_shop_location'] = to_boolean($attributes['determine_price_by_shop_location']);
        }
        if (isset($attributes['only_for_shop_location'])) {
            $updateAttributes['only_for_shop_location'] = to_boolean($attributes['only_for_shop_location']);
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
