<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\CategoryImage;
use App\Repositories\Contracts\CategoryImageRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CategoryImageRepository extends Repository implements CategoryImageRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(
        CategoryImage      $model,
        protected Category $categoryModel,
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getCategoryImagesSearchFilterPaginated(
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->categoryModel->newQuery();

        $query
            ->with(['categoryImage', 'categoryImage.creator', 'categoryImage.updater', 'categoryImage.deleter'])
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->withWhereHas('parent', function ($q) use ($search) {
                        $q->orWhereLike('escaped_name', $search);
                    })
                    ->orWhereLike('escaped_name', $search);
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
