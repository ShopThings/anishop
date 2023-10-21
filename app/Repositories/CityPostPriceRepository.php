<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Models\CityPostPrice;
use App\Repositories\Contracts\CityPostPriceRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CityPostPriceRepository extends Repository implements CityPostPriceRepositoryInterface
{
    use RepositoryTrait;

    protected bool $useSoftDeletes = false;

    public function __construct(CityPostPrice $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getPostPricesSearchFilterPaginated(
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
                ->withWhereHas('city', function ($q) use ($search) {
                    $q
                        ->where('is_published', DatabaseEnum::DB_YES)
                        ->whereLike('name', $search);
                });
        });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
