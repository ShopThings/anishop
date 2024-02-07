<?php

namespace App\Repositories;

use App\Models\ProductAttributeCategory;
use App\Repositories\Contracts\ProductAttributeCategoryRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductAttributeCategoryRepository extends Repository implements ProductAttributeCategoryRepositoryInterface
{
    use RepositoryTrait;

    protected bool $useSoftDeletes = false;

    public function __construct(ProductAttributeCategory $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCategoriesSearchFilterPaginated(
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query
            ->with(['productAttr', 'category'])
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->WhereHas('productAttr', function ($q) use ($search) {
                        $q->orWhereLike('title', $search);
                    })
                    ->WhereHas('category', function ($q) use ($search) {
                        $q->orWhereLike([
                            'latin_name',
                            'escaped_name',
                        ], $search);
                    });
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
