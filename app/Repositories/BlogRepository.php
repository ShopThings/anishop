<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BlogRepository extends Repository implements BlogRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getBlogsSearchFilterPaginated(
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
                        $q->orWhereLike([
                            'escaped_name',
                            'keywords',
                        ], $search);
                    })
                    ->orWhereLike([
                        'blogs.escaped_title',
                        'blogs.keywords',
                    ], $search);
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
