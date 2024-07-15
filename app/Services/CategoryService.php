<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Http\Requests\Filters\HomeCategoryFilter;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
    public function getCategories(Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository
            ->newWith(['parent', 'creator', 'updater', 'deleter', 'categoryImage'])
            ->getCategoriesSearchFilterPaginated(filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getCategoriesCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @inheritDoc
     */
    public function getPublishedCategories(Filter $filter, bool $containMenuCategories = false): Collection
    {
        $where = new WhereBuilder();
        $where->whereEqual('is_published', DatabaseEnum::DB_YES);

        if ($containMenuCategories) {
            $where->whereEqual('show_in_menu', DatabaseEnum::DB_YES);
        }

        $repo = $this->repository;
        $with = [];

        if ($filter instanceof HomeCategoryFilter) {
            $where
                ->when($filter->getParent(), function (WhereBuilderInterface $q, $parent) {
                    $q->whereEqual('parent_id', $parent);
                })
                ->when($filter->getAncestry(), function (WhereBuilderInterface $q, $ancestry) {
                    $q->group(function (WhereBuilderInterface $q) use ($ancestry) {
                        $q
                            ->orWhereEqual('parent_id', $ancestry)
                            ->orWhereRegexp('ancestry', get_db_ancestry_regex_string($ancestry));
                    });
                })
                ->when(!is_null($filter->getLevel()), function (WhereBuilderInterface $q) use ($filter) {
                    $q->whereEqual('level', $filter->getLevel());
                })
                ->when($filter->getWithChildren(), function () use (&$with) {
                    $with[] = 'children';
                });
        }

        $where->when($filter->getSearchText(), function (WhereBuilderInterface $where, $searchText) {
            $where->group(function (WhereBuilderInterface $where) use ($searchText) {
                $where->orWhereLike(['escaped_name', 'name'], $searchText);
            });
        });

        // I needed to add 'children' in the way shown and then add 'parent' relations
        $with[] = 'parent';

        if ($filter->getLimit()) {
            return collect(
                $repo
                    ->newWith($with)
                    ->paginate(
                        where: $where->build(),
                        limit: $filter->getLimit(),
                        order: [
                            'priority' => 'asc',
                            'id' => 'asc',
                        ]
                    )
                    ->items()
            );
        } else {
            return $repo
                ->newWith($with)
                ->all(
                    where: $where->build(),
                    order: [
                        'priority' => 'asc',
                        'id' => 'asc',
                    ]
                );
        }
    }

    /**
     * @inheritDoc
     */
    public function getSliderCategories(): Collection
    {
        return $this->repository->getSliderCategories();
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'parent_id' => ($attributes['parent'] ?? null) ?: null,
            'name' => $attributes['name'],
            'escaped_name' => NumberConverter::toEnglish($attributes['name']),
            'ancestry' => $this->getAncestry($attributes['parent'] ?? null),
            'level' => $this->getLevel($attributes['parent'] ?? null),
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
    public function updateById($id, array $attributes, ?Model $model = null): ?Model
    {
        $updateAttributes = [];

        DB::beginTransaction();

        $res2 = true;

        if (isset($attributes['parent'])) {
            if (is_null($model)) {
                $model = $this->getById($id);
            }

            $newLevel = $this->getLevel($updateAttributes['parent_id']);
            $prevLevel = $model->level;

            $updateAttributes['parent_id'] = $attributes['parent'] ?: null;
            $updateAttributes['ancestry'] = $this->getAncestry($attributes['parent']);
            $updateAttributes['level'] = $newLevel;

            $changeFreq = $newLevel - $prevLevel;

            if ($changeFreq !== 0) {
                $res2 = $this->repository->updateChildrenLevel($id, $changeFreq);
            }
        }
        if (isset($attributes['name'])) {
            $updateAttributes['name'] = $attributes['name'];
            $updateAttributes['escaped_name'] = NumberConverter::toEnglish($attributes['name']);
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

        if (!$res || !$res2) {
            DB::rollBack();
            return null;
        }

        return $this->getById($id);
    }

    /**
     * @param $parentId
     * @return int
     */
    protected function getLevel($parentId): int
    {
        if (is_null($parentId) || !is_numeric($parentId)) return 0;

        /**
         * @var Category $category
         */
        $category = $this->getById($parentId);
        return ((int)$category->level) + 1;
    }

    /**
     * @param $parentId
     * @return string|null
     */
    protected function getAncestry($parentId): ?string
    {
        if (is_null($parentId) || !is_numeric($parentId)) return null;

        $ancestry = [];
        /**
         * @var Category $category
         */
        while (
            !is_null($parentId) &&
            is_numeric($parentId) &&
            ($category = $this->getById($parentId)) instanceof Model
        ) {
            $parentId = $category->id;
            $ancestry[] = $parentId;
            $parentId = $category->parent_id;
        }

        if (!count($ancestry)) return null;

        return implode('|', $ancestry);
    }
}
