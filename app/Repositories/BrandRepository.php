<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Models\Brand;
use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Collection\Collection;

class BrandRepository extends Repository implements BrandRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getPublishedFilteredBrands(
        Filter $filter,
        array  $columns = ['*']
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query
            ->published()
            ->where('is_deletable', DatabaseEnum::DB_YES)
            ->with('image')
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query
                    ->whereHas('products', function ($q) use ($search) {
                        $q->where(function ($q) use ($search) {
                            $q
                                ->orWhereLike(['escaped_title', 'keywords'], $search)
                                ->orWhereHas('category', function ($q) use ($search) {
                                    $q->whereLike('escaped_name', $search);
                                });
                        });

                    })
                    ->orWhereLike(['name', 'latin_name', 'keywords'], $search);
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
