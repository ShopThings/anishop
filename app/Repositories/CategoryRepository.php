<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
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
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query->when($search, function (Builder $query, string $search) {
            $query
                ->withWhereHas('parent', function ($q) use ($search) {
                    $q->orWhereLike('escaped_name', $search);
                })
                ->orWhereLike('categories.escaped_name', $search);
        });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
