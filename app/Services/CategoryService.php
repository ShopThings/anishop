<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class CategoryService extends Service implements CategoryServiceInterface
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getCategories(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getCategoriesSearchFilterPaginated(
            search: $searchText,
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'parent_id' => $attributes['parent'] ?? null,
            'name' => $attributes['name'],
            'escaped_name' => NumberConverter::toEnglish($attributes['name']),
            'ancestry' => $this->_getAncestry($attributes['parent'] ?? null),
            'level' => $attributes['level'],
            'priority' => $attributes['priority'],
            'show_in_menu' => to_boolean($attributes['show_in_menu']),
            'show_in_search_side_menu' => to_boolean($attributes['show_in_search_side_menu']),
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

        if (isset($attributes['parent'])) {
            $updateAttributes['parent_id'] = $attributes['parent'];
            $updateAttributes['ancestry'] = $this->_getAncestry($attributes['parent']);
        }
        if (isset($attributes['name'])) {
            $updateAttributes['name'] = $attributes['name'];
            $updateAttributes['escaped_name'] = NumberConverter::toEnglish($attributes['name']);
        }
        if (isset($attributes['level'])) {
            $updateAttributes['level'] = $attributes['level'];
        }
        if (isset($attributes['priority'])) {
            $updateAttributes['priority'] = $attributes['priority'];
        }
        if (isset($attributes['show_in_menu'])) {
            $updateAttributes['show_in_menu'] = to_boolean($attributes['show_in_menu']);
        }
        if (isset($attributes['show_in_search_side_menu'])) {
            $updateAttributes['show_in_search_side_menu'] = to_boolean($attributes['show_in_search_side_menu']);
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

    /**
     * @param $parentId
     * @return string|null
     */
    protected function _getAncestry($parentId): ?string
    {
        if (is_null($parentId) || !is_numeric($parentId)) return null;

        $ancestry = [];
        do {
            /**
             * @var Category $category
             */
            $category = $this->getById($parentId);
            $ancestry[] = $parentId;
            $parentId = $category->id;
        } while ($category->parent());

        return implode('|', $ancestry);
    }
}
