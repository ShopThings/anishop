<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Menus\MenuPlacesEnum;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Support\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MenuRepository extends Repository implements MenuRepositoryInterface
{
    public function __construct(
        Menu               $model,
        protected MenuItem $menuItemModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getHomeMenus(MenuPlacesEnum $placeIn): Collection
    {
        return $this->model->newQuery()
            ->with([
                'items' => function ($query) {
                    $query
                        ->whereNull('parent_id')
                        ->where('is_published', DatabaseEnum::DB_YES)
                        ->orderBy('priority')
                        ->orderBy('id');
                },
                'items.children',
            ])
            ->where('is_published', DatabaseEnum::DB_YES)
            ->where('place_in', $placeIn->value)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreateItems(array $items): Collection
    {
        $items = $this->_refineItems($items);
        return $this->_storeItems($items);
    }

    /**
     * @param array $items
     * @return Collection
     */
    private function _storeItems(array $items): Collection
    {
        $modified = collect();

        foreach ($items as &$item) {
            $children = $item['children'] ?? null;
            unset($item['children']);

            $model = $this->_storeSingleItem($item);

            if ($model instanceof Model) {
                $modified->add($model);

                if (!is_null($children)) {
                    foreach ($children as &$child) {
                        $child['parent_id'] = $model->id;

                        if (isset($child['children']) && is_array($child['children']) && count($child['children'])) {
                            $this->_storeItems($child['children']);
                        } else {
                            unset($child['children']);
                            $this->_storeSingleItem($child);
                        }
                    }
                }
            }
        }

        return $modified;
    }

    /**
     * @param array $item
     * @return Model|null
     */
    private function _storeSingleItem(array $item): ?Model
    {
        if (isset($item['id'])) {
            $isUpdated = $this->menuItemModel->findOrFail($item['id'])->update($item);
            if ($isUpdated)
                $createdOrUpdated = $this->menuItemModel::query()->find($item['id']);
        } else {
            $createdOrUpdated = $this->menuItemModel::create($item);
        }
        return $createdOrUpdated;
    }

    /**
     * @param array $items
     * @return array
     */
    private function _refineItems(array $items): array
    {
        if (!count($items)) return [];

        $refined = [];
        foreach ($items as $key => $item) {
            $tmp = $item;
            unset($tmp['tmp_id']);
            unset($tmp['can_have_children']);
            $tmp['priority'] = $key;

            if (isset($tmp['children']) && is_array($tmp['children'])) {
                $tmpRefined = $this->_refineItems($tmp['children']);
                foreach ($tmpRefined as $i) {
                    $refined[] = $i;
                }
            }

            $refined[] = $tmp;
        }
        return $refined;
    }
}
