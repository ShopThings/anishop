<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Models\CityPostPrice;
use App\Repositories\Contracts\CityPostPriceRepositoryInterface;
use App\Support\Filter;
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
            ->with(['city', 'city.province'])
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query
                    ->when($filter->getRelationSearch(), function ($q) use ($search) {
                        $q->orWhereHas('city', function ($q) use ($search) {
                            $q->where(function ($q) use ($search) {
                                $q
                                    ->where('is_published', DatabaseEnum::DB_YES)
                                    ->orWhereLike('name', $search);
                            });
                        });
                    });
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
