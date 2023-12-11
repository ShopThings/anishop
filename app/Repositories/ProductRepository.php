<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Products\ProductOrderTypesEnum;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends Repository implements ProductRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(
        Product                   $model,
        protected ProductProperty $productPropertyModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getProductsSearchFilterPaginated(
        array                     $columns = ['*'],
        Filter                    $filter = null,
        GetterExpressionInterface $where = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->withWhereHas('brand', function ($q) use ($search) {
                        $q->orWhereLike([
                            'latin_name',
                            'escaped_name',
                            'keywords',
                        ], $search);
                    })
                    ->withWhereHas('category', function ($q) use ($search) {
                        $q->orWhereLike([
                            'latin_name',
                            'escaped_name',
                        ], $search);
                    })
                    ->withWhereHas('items', function ($q) use ($search) {
                        $q->orWhereLike([
                            'color_name',
                            'size',
                            'guarantee',
                        ], $search);
                    })
                    ->withWhereHas('productAttrValues.attrValues', function ($q) use ($search) {
                        $q->orWhereLike('attribute_value', $search);
                    })
                    ->withWhereHas('productAttrValues.attrValues.productAttr', function ($q) use ($search) {
                        $q->orWhereLike('title', $search);
                    })
                    ->orWhereLike([
                        'escaped_title',
                        'keywords',
                    ], $search);
            })
            ->when($where, function ($q, $where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            });

        if ($filter instanceof HomeProductFilter) {
            $query = $this->_getBlogOrderEquivalent($query, $filter->getProductOrder());

            $brand = $filter->getBrand();
            $category = $filter->getCategory();
            $isSpecial = $filter->getIsSpecial();
            $isAvailable = $filter->getIsAvailable();

            $query
                ->when($brand, function (Builder $query, int $brand) {
                    $query->where('products.brand_id', $brand);
                })
                ->when($category, function (Builder $query, int $category) {
                    $query->where('products.category_id', $category);
                })
                ->when($isSpecial, function (Builder $query) {
                    $query->whereHas('items', function ($q) {
                        $q->where('is_special', DatabaseEnum::DB_YES);
                    });
                })
                ->when($isAvailable, function (Builder $query) {
                    $query->whereHas('items', function ($q) {
                        $q->where('is_available', DatabaseEnum::DB_YES);
                    });
                });
        }

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreateProducts(array $products): Model|Collection
    {
        $modified = collect();

        foreach ($products as $product) {
            if (isset($product['id'])) {
                $isUpdated = $this->productPropertyModel->findOrFail($product['id'])->update($product);

                if ($isUpdated)
                    $modified->add($this->productPropertyModel::first($product['id']));
            } else {
                $created = $this->productPropertyModel::create($product);

                if ($created instanceof Model)
                    $modified->add($created);
            }
        }

        return $modified;
    }

    /**
     * @param Builder $query
     * @param ProductOrderTypesEnum|null $order
     * @return Builder
     */
    private function _getBlogOrderEquivalent(Builder $query, ?ProductOrderTypesEnum $order): Builder
    {
        if (null === $order) return $query->orderBy('id', 'desc');

        $visitableTable = config('visitor.table_name');

        return match ($order) {
            ProductOrderTypesEnum::NEWEST => $query->orderBy('id', 'desc'),
            ProductOrderTypesEnum::OLDEST => $query->orderBy('id', 'asc'),
            ProductOrderTypesEnum::MOST_VIEWED => $query->select(['products.*'])
                ->selectRaw('COUNT(DISTINCT(' . $visitableTable . '.ip)) AS unique_visit_count')
                ->leftJoin(
                    $visitableTable,
                    function ($join) use ($visitableTable) {
                        $join->on('products.id', $visitableTable . '.visitable_id')
                            ->where('visitable_type', Product::class);
                    }
                )
                ->orderBy('unique_visit_count', 'desc')
                ->groupBy('products.id'),
        };
    }
}
