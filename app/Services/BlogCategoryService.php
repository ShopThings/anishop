<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Repositories\Contracts\BlogCategoryRepositoryInterface;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class BlogCategoryService extends Service implements BlogCategoryServiceInterface
{
    public function __construct(
        protected BlogCategoryRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getCategories(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('blog_categories');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike([
                'escaped_name',
                'keywords',
            ], $search);
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
    public function getSideCategories(): Collection
    {
        $where = new WhereBuilder('blog_categories');
        $where->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->repository->all(
            columns: ['id', 'name', 'escaped_name', 'slug', 'keywords'],
            where: $where->build(),
            order: ['priority' => 'asc', 'id' => 'asc']
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'name' => $attributes['name'],
            'escaped_name' => NumberConverter::toEnglish($attributes['name']),
            'level' => $attributes['level'],
            'priority' => $attributes['priority'],
            'show_in_menu' => to_boolean($attributes['show_in_menu']),
            'show_in_side_menu' => to_boolean($attributes['show_in_search_side_menu']),
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
            $updateAttributes['escaped_name'] = NumberConverter::toEnglish($attributes['name']);
        }
        if (isset($attributes['priority'])) {
            $updateAttributes['priority'] = $attributes['priority'];
        }
        if (isset($attributes['show_in_menu'])) {
            $updateAttributes['show_in_menu'] = to_boolean($attributes['show_in_menu']);
        }
        if (isset($attributes['show_in_side_menu'])) {
            $updateAttributes['show_in_side_menu'] = to_boolean($attributes['show_in_side_menu']);
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
