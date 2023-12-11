<?php

namespace App\Services;

use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Services\Contracts\MenuServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use function App\Support\Helper\to_boolean;

class MenuService extends Service implements MenuServiceInterface
{
    public function __construct(
        protected MenuRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getMenus(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('menus');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('title', $search);
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
            'menu_place_id' => $attributes['menu_place'],
            'title' => $attributes['title'],
            'priority' => $attributes['priority'] ?? 0,
            'options' => $attributes['options'] ?? [],
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

        if (isset($attributes['menu_place'])) {
            $updateAttributes['menu_place_id'] = $attributes['menu_place'];
        }
        if (isset($attributes['title'])) {
            $updateAttributes['title'] = $attributes['title'];
        }
        if (isset($attributes['priority'])) {
            $updateAttributes['priority'] = $attributes['priority'];
        }
        if (isset($attributes['options'])) {
            $updateAttributes['options'] = $attributes['options'];
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

    /**
     * @inheritDoc
     */
    public function modifyMenuItems(int $menuId, array $menus): Collection
    {
        if (!count($menus))
            throw new InvalidArgumentException('وارد نمودن حداقل یک منو اجباری می‌باشد.');

        foreach ($menus as &$item) {
            $item['menu_id'] = $menuId;
        }

        return $this->repository->updateOrCreateItems($menus);
    }
}
