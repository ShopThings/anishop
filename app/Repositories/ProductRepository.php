<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Enums\Products\ProductOrderTypesEnum;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Requests\Filters\HomeProductSideFilter;
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
use Illuminate\Support\Facades\DB;
use function App\Support\Helper\get_db_ancestry_regex_string;

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
                    ->whereHas('productAttrValues.attrValues', function ($q) use ($search) {
                        $q->orWhereLike('attribute_value', $search);
                    })
                    ->whereHas('productAttrValues.attrValues.productAttr', function ($q) use ($search) {
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
            // need published stuffs
            $query = $query->published();
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

            // dynamic filters
            $dynamicFilters = $filter->getDynamicFilters();

            if ($dynamicFilters) {
                // iterate to dynamic filter and check 'attribute_id' from attributes
                // and 'attribute_value' from attribute_values tables
                foreach ($dynamicFilters as $attribute => $values) {
                    $query->orWhereHas('productAttrValues.attrValues', function ($q) use ($attribute, $values) {
                        $q->orWhereHas('productAttrValues.attrValues.productAttr', function ($q) use ($attribute, $values) {
                            $q->where('id', $attribute);

                            foreach ($values as $value) {
                                $q->orWhereLike('attribute_value', $value);
                            }
                        });
                    });
                }
            }
        }

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getFilterColorsAndSizes(HomeProductSideFilter $filter): Collection
    {
        $query = $this->model::published();
        $query = $this->_considerProductSideFilter($query, $filter);
        $query->withWhereHas('items', function ($q) {
            $q->published();
        });

        return $query->get(['color_name as name', 'color_hex as hex', 'size']);
    }

    /**
     * @inheritDoc
     */
    public function getFilterPriceRange(HomeProductSideFilter $filter): array
    {
        $query = $this->model::published();
        $query = $this->_considerProductSideFilter($query, $filter);
        $query
            ->withWhereHas('items', function ($q) {
                $q->published();
            })
            ->selectRaw('MAX(product_properties.price) as max_price')
            ->selectRaw('MIN(product_properties.price) as min_price');

        $res = $query->first();

        return [
            'max' => $res->max_price ?? 0,
            'min' => $res->min_price ?? 0,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDynamicFilters(HomeProductSideFilter $filter): Collection
    {
        $category = $filter->getCategory();

        // dynamic filter MUST be on a specific category,
        // otherwise there is no good reason to have any extra filters
        if (!$category) return collect();


        $query = $this->model::published()
            // -productAttrValues is "product_attribute_products" table relation
            // -attrValues is "product_attribute_values" table relation
            ->withWhereHas('productAttrValues.attrValues', function ($q) use ($category) {
                $q
                    // we go nested inside relations to get product attributes for a specific category.
                    //   -productAttr is "product_attributes" table relation
                    //   -productAttrs is "product_attribute_categories" table relation
                    ->whereHas('productAttr.productAttrs', function ($q) use ($category) {
                        $q->where('category_id', $category);
                    })
                    ->where('id', $category);
            })
            // -productAttrValues is "product_attribute_products" table relation
            // -attrValues is "product_attribute_values" table relation
            // -productAttr is "product_attributes" table relation
            ->with('productAttrValues.attrValues.productAttr')
            ->orderBy('product_attribute_values.priority');

        return $query->get([
            'product_attributes.id',
            'product_attributes.title',
            'product_attributes.type',
            'product_attribute_values.attribute_value',
            'product_attribute_values.id as attribute_value_id',
        ]);
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
     * @inheritDoc
     */
    public function updatePriceUsingPercentage(
        $id,
        int $percentage,
        ChangeMultipleProductPriceTypesEnum $changeType
    ): bool
    {
        $query = $this->productPropertyModel->newQuery();

        if (is_array($id)) $query->whereIn('product_id', $id);
        else $query->where('product_id', $id);

        $percentage = abs($percentage);
        $percentage = !in_array($percentage, [0, 100]) ? $percentage % 100 : $percentage;
        $percentage = match ($changeType) {
            ChangeMultipleProductPriceTypesEnum::INCREASE => (100 + $percentage),
            ChangeMultipleProductPriceTypesEnum::DECREASE => (100 - $percentage),
        };
        $percentage = (float)($percentage / 100);

        return !!$query->update([
            'price' => DB::raw('price * ' . $percentage),
            'discounted_price' => DB::raw('discounted_price * ' . $percentage),
        ]);
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

    /**
     * @param Builder $query
     * @param HomeProductSideFilter $filter
     * @return Builder
     */
    private function _considerProductSideFilter(Builder $query, HomeProductSideFilter $filter): Builder
    {
        $category = $filter->getCategory();
        $festival = $filter->getFestival();

        return $query
            ->when($category, function (Builder $query, $category) {
                $query->withWhereHas('category', function ($q) use ($category) {
                    $q
                        ->where('id', $category)
                        ->whereRegex('ancestry', get_db_ancestry_regex_string($category));
                });
            })
            ->when($festival, function (Builder $query, $festival) {
                $query->whereHas('festivals', function ($q) use ($festival) {
                    $q
                        ->where('id', $festival)
                        ->published();
                });
            });
    }
}
