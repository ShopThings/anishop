<?php

namespace App\Repositories;

use App\Models\ProductAttributeCategory;
use App\Repositories\Contracts\ProductAttributeCategoryRepositoryInterface;
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
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->withWhereHas('productAttr', function ($q) use ($search) {
                        $q->orWhereLike('title', $search);
                    })
                    ->withWhereHas('category', function ($q) use ($search) {
                        $q->orWhereLike([
                            'latin_name',
                            'escaped_name',
                        ], $search);
                    });
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
