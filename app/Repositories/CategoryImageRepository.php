<?php

namespace App\Repositories;

use App\Models\CategoryImage;
use App\Repositories\Contracts\CategoryImageRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CategoryImageRepository extends Repository implements CategoryImageRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(CategoryImage $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getCategoryImagesSearchFilterPaginated(
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
                ->withWhereHas('category', function ($q) use ($search) {
                    $q
                        ->withWhereHas('parent', function ($q) use ($search) {
                            $q->orWhereLike('escaped_name', $search);
                        })
                        ->orWhereLike('escaped_name', $search);
                });
        });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
