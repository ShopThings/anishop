<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Repositories\Contracts\SendMethodRepositoryInterface;
use App\Services\Contracts\SendMethodServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\Traits\ImageFieldTrait;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
    public function getMethodsCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @inheritDoc
     */
    public function getHomeMethods(): Collection
    {
        $where = new WhereBuilder('send_methods');
        $where->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->repository
            ->newWith('image')
            ->all(where: $where->build(), order: ['priority' => 'asc']);
    }

    /**
     * @inheritDoc
     */
    public function getSpecificMethod(int $id, bool $publishedOne = true): ?Model
    {
        $where = new WhereBuilder('send_methods');
        $where
            ->whereEqual('id', $id)
            ->when($publishedOne, function ($where) {
                $where->whereEqual('is_published', DatabaseEnum::DB_YES);
            });

        return $this->repository->findWhere($where->build());
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attributes['image'] = $this->getImageId($attributes['image'] ?? null);

        $attrs = [
            'title' => $attributes['title'],
            'description' => $attributes['description'] ?? '',
            'image_id' => $attributes['image'],
            'price' => $attributes['price'] ?? 0,
            'priority' => abs($attributes['priority'] ?? 0),
            'determine_price_by_shop_location' => to_boolean($attributes['determine_price_by_shop_location']),
            'only_for_shop_location' => to_boolean($attributes['only_for_shop_location']),
            'apply_number_of_shipments_on_price' => to_boolean($attributes['apply_number_of_shipments_on_price']),
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
        if (isset($attributes['apply_number_of_shipments_on_price'])) {
            $updateAttributes['apply_number_of_shipments_on_price'] = to_boolean($attributes['apply_number_of_shipments_on_price']);
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
