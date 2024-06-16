<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CategoryRepository extends Repository implements CategoryRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getCategoriesSearchFilterPaginated(
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query->when($search, function (Builder $query, string $search) use ($filter) {
            $query
                ->when($filter->getRelationSearch(), function ($q) use ($search) {
                    $q->orWhereHas('parent', function ($q) use ($search) {
                        $q->whereLike('escaped_name', $search);
                    });
                })
                ->orWhereLike('categories.escaped_name', $search);
        });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getSliderCategories(): Collection
    {
        return $this->model->published()
            ->where('show_in_slider', DatabaseEnum::DB_YES)
            ->leftJoin(
                'category_images',
                function ($join) {
                    $join->on('categories.id', 'category_images.category_id');
                }
            )
            ->join(
                'file_manager',
                function ($join) {
                    $join->on('file_manager.id', 'category_images.image_id');
                }
            )
            ->get([
                'categories.id',
                'categories.name',
                'categories.slug',
                'file_manager.full_path',
            ]);
    }
}
