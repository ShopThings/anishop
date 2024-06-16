<?php

namespace App\Repositories;

use App\Models\Festival;
use App\Models\ProductFestival;
use App\Repositories\Contracts\FestivalRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FestivalRepository extends Repository implements FestivalRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(
        Festival                  $model,
        protected ProductFestival $productFestivalModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getFestivalProductsSearchFilterPaginated(
        int    $festivalId,
        array  $columns = ['*'],
        Filter $filter = null,
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->productFestivalModel->newQuery();
        $query
            ->with(['festival', 'product'])
            ->where('festival_id', $festivalId)
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query->when($filter->getRelationSearch(), function ($q) use ($search) {
                    $q->orWhereHas('product', function ($q) use ($search) {
                        $q
                            ->orWhereHas('brand', function ($q2) use ($search) {
                                $q2->where(function ($q) use ($search) {
                                    $q->orWhereLike([
                                        'latin_name',
                                        'escaped_name',
                                        'keywords',
                                    ], $search);
                                });
                            })
                            ->orWhereHas('category', function ($q2) use ($search) {
                                $q2->where(function ($q) use ($search) {
                                    $q->orWhereLike([
                                        'latin_name',
                                        'escaped_name',
                                    ], $search);
                                });
                            })
                            ->orWhereLike([
                                'escaped_title',
                                'keywords',
                            ], $search);
                    });
                });
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function addProductToFestival(int $productId, int $festivalId, $discountPercentage): Builder|Model
    {
        return $this->productFestivalModel->newQuery()
            ->updateOrCreate([
                'product_id' => $productId,
                'festival_id' => $festivalId,
            ], [
                'discount_percentage' => $discountPercentage,
            ]);
    }

    /**
     * @inheritDoc
     */
    public function removeProductFromFestival(int $productId, int $festivalId): bool
    {
        return $this->productFestivalModel->newQuery()
            ->where('product_id', $productId)
            ->where('festival_id', $festivalId)
            ->delete();
    }

    /**
     * @inheritDoc
     */
    public function removeProductsFromFestival(int $festivalId, array $ids): bool
    {
        return $this->productFestivalModel->newQuery()
            ->whereIn('product_id', $ids)
            ->delete();
    }
}
