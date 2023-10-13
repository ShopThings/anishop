<?php

namespace App\Repositories;

use App\Models\Festival;
use App\Repositories\Contracts\FestivalRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FestivalRepository extends Repository implements FestivalRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(Festival $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $festivalId
     * @param array $columns
     * @param string|null $search
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getFestivalProductsSearchFilterPaginated(
        int     $festivalId,
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query
            ->where('festival_id', $festivalId)
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->withWhereHas('items', function ($q) use ($search) {
                        $q
                            ->withWhereHas('brand', function ($q2) use ($search) {
                                $q2
                                    ->orWhereLike([
                                        'latin_name',
                                        'escaped_name',
                                        'keywords',
                                    ], $search);
                            })
                            ->withWhereHas('category', function ($q2) use ($search) {
                                $q2
                                    ->orWhereLike([
                                        'latin_name',
                                        'escaped_name',
                                    ], $search);
                            })
                            ->orWhereLike([
                                'escaped_title',
                                'keywords',
                            ], $search);
                    });
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
