<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Support\Repository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

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
                $createdOrUpdated = $this->menuItemModel::first($item['id']);
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
        $refined = [];
        foreach ($items as $key => $item) {
            $tmp = $item;
            unset($tmp['tmp_id']);
            unset($tmp['can_have_children']);
            $tmp['priority'] = $key;

            if (isset($tmp['children']) && is_array($tmp['children'])) {
                $tmpRefined = $this->_refineItems($tmp['children']);
                foreach ($tmpRefined as $k => $i) {
                    $tmp2 = $i;
                    unset($tmp2['tmp_id']);
                    unset($tmp2['can_have_children']);
                    $tmp2['priority'] = $k;

                    $refined[] = $tmp2;
                }
            }

            $refined[] = $tmp;
        }
        return $refined;
    }
}
