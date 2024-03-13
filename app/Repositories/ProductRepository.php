<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Enums\Products\ProductOrderTypesEnum;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Http\Requests\Filters\ProductFilter;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductProperty;
use App\Models\RelatedProduct;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Support\Filter;
use App\Support\Model\CaseWhen;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductRepository extends Repository implements ProductRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(
        Product                   $model,
        protected ProductProperty $productPropertyModel,
        protected ProductGallery  $productGalleryModel,
        protected RelatedProduct  $relatedProductModel
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
            ->with(['brand', 'category', 'image'])
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query
                    ->when($filter->getRelationSearch(), function ($q) use ($search) {
                        $q
                            ->orWhereHas('brand', function ($q) use ($search) {
                                $q->where(function ($q) use ($search) {
                                    $q->orWhereLike([
                                        'latin_name',
                                        'escaped_name',
                                        'keywords',
                                    ], $search);
                                });
                            })
                            ->orWhereHas('category', function ($q) use ($search) {
                                $q->where(function ($q) use ($search) {
                                    $q->orWhereLike([
                                        'latin_name',
                                        'escaped_name',
                                    ], $search);
                                });
                            })
                            ->orWhereHas('items', function ($q) use ($search) {
                                $q->where(function ($q) use ($search) {
                                    $q->orWhereLike([
                                        'color_name',
                                        'size',
                                        'guarantee',
                                    ], $search);
                                });
                            })
                            ->orWhereHas('productAttrValues.attrValues', function ($q) use ($search) {
                                $q->whereLike('attribute_value', $search);
                            })
                            ->orWhereHas('productAttrValues.attrValues.productAttr', function ($q) use ($search) {
                                $q->whereLike('title', $search);
                            });
                    })
                    ->orWhereLike([
                        'escaped_title',
                        'keywords',
                    ], $search);
            })
            ->when($where, function ($q, $where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            });

        // add extra filter product filter instance
        if ($filter instanceof ProductFilter) {
            $ids = $filter->getIds();
            if (!is_null($ids) && count($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        // add extra filter for home product filter
        if ($filter instanceof HomeProductFilter) {
            // need published stuffs
            $query = $query->published();
            $query = $this->_getProductOrderEquivalent($query, $filter->getProductOrder());

            $brand = $filter->getBrand();
            $brands = $filter->getBrands();
            $category = $filter->getCategory();
            $isSpecial = $filter->getIsSpecial();
            $isAvailable = $filter->getIsAvailable();
            $priceRange = $filter->getPriceRange();

            $query
                ->when($brand, function (Builder $query, int $brand) {
                    $query->where('brand_id', $brand);
                })
                ->when(count($brands), function (Builder $query) use ($brands) {
                    $query->whereIn('brand_id', $brands);
                })
                ->when($category, function (Builder $query, int $category) {
                    $query->where('category_id', $category);
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
                })
                ->when(count($priceRange) == 2, function (Builder $query) use ($priceRange) {
                    $query->whereHas('items', function ($q) use ($priceRange) {
                        $q
                            ->where(function ($q2) use ($priceRange) {
                                return (new CaseWhen($q2))
                                    // Check if discounted price is within the range
                                    ->when(
                                        '(discounted_from IS NULL AND discounted_until IS NOT NULL AND discounted_until >= ?) OR' .
                                        '(discounted_from IS NOT NULL AND discounted_until IS NULL AND discounted_from <= ?) OR' .
                                        '(discounted_from IS NOT NULL AND discounted_until IS NOT NULL AND discounted_from <= ? AND discounted_until >= ?)'
                                        , 'discounted_price >= ?', [now(), now(), now(), now(), $priceRange[0]])
                                    // If discounted conditions are not met, check the regular price
                                    ->else('price >= ?', [$priceRange[0]], 'where');
                            })
                            ->where(function ($q2) use ($priceRange) {
                                return (new CaseWhen($q2))
                                    // Check if discounted price is within the range
                                    ->when(
                                        '(discounted_from IS NULL AND IS NOT NULL discounted_until AND discounted_until >= ?) OR' .
                                        '(discounted_from IS NOT NULL AND discounted_until IS NULL AND discounted_from <= ?) OR' .
                                        '(discounted_from IS NOT NULL AND discounted_until IS NOT NULL AND discounted_from <= ? AND discounted_until >= ?)'
                                        , 'discounted_price <= ?', [now(), now(), now(), now(), $priceRange[1]])
                                    // If discounted conditions are not met, check the regular price
                                    ->else('price <= ?', [$priceRange[1]], 'where');
                            });
                    });
                });

            // dynamic filters
            $dynamicFilters = $filter->getDynamicFilters();

            if ($dynamicFilters) {
                // iterate to dynamic filter and check 'attribute_id' from attributes
                // and 'attribute_value' from attribute_values tables
                foreach ($dynamicFilters as $attribute => $values) {
                    $query->orWhereHas('productAttrValues.attrValues', function ($q) use ($attribute, $values) {
                        $q->where(function () use ($q, $attribute, $values) {
                            $q->orWhereHas(
                                'productAttrValues.attrValues.productAttr',
                                function ($q2) use ($q, $attribute, $values) {
                                    $q2->where(function () use ($q, $q2, $attribute, $values) {
                                        $q->where('id', $attribute);
                                        $q2->orWhereIn('attribute_value', $values);
                                    });
                                }
                            );
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
    public function getFilterBrands(HomeProductSideFilter $filter): Collection
    {
        $query = $this->productPropertyModel::published();
        $query = $this->_considerProductSideFilter($query, $filter);
        $query->with('product.brand');

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getFilterColorsAndSizes(HomeProductSideFilter $filter): Collection
    {
        $query = $this->productPropertyModel::published();
        $query = $this->_considerProductSideFilter($query, $filter);

        return $query->get(['color_name as name', 'color_hex as hex', 'size']);
    }

    /**
     * @inheritDoc
     */
    public function getFilterPriceRange(HomeProductSideFilter $filter): array
    {
        $query = $this->productPropertyModel::published();
        $query = $this->_considerProductSideFilter($query, $filter);
        $query
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
                    ->orderBy('priority');
            })
            // -productAttrValues is "product_attribute_products" table relation
            // -attrValues is "product_attribute_values" table relation
            // -productAttr is "product_attributes" table relation
            ->with('productAttrValues.attrValues.productAttr');

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function createGallery(int $productId, array $images): bool
    {
        DB::beginTransaction();

        // first delete all images that not exists in $images variable from db
        $res = (bool)$this->productGalleryModel->newQuery()
            ->where('product_id', $productId)
            ->whereNotIn('image_id', $images)
            ->delete();
        if (!$res) {
            DB::rollBack();
            return false;
        }

        // then update/create provided images
        foreach ($images as $image) {
            $model = $this->productGalleryModel::updateOrCreate([
                'product_id' => $productId,
                'image_id' => $image,
            ]);

            if (!$model instanceof Model) {
                DB::rollBack();
                return false;
            }
        }

        DB::commit();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function createRelatedProducts(int $productId, array $products): bool
    {
        DB::beginTransaction();

        // first delete all related products that not exists in $products variable from db
        $res = (bool)$this->relatedProductModel->newQuery()
            ->where('product_id', $productId)
            ->whereNotIn('related_id', $products)
            ->delete();
        if (!$res) {
            DB::rollBack();
            return false;
        }

        // then update/create provided related products
        foreach ($products as $product) {
            $model = $this->relatedProductModel::updateOrCreate([
                'product_id' => $productId,
                'related_id' => $product,
            ]);

            if (!$model instanceof Model) {
                DB::rollBack();
                return false;
            }
        }

        DB::commit();
        return true;
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
    private function _getProductOrderEquivalent(Builder $query, ?ProductOrderTypesEnum $order): Builder
    {
        if (null === $order) return $query->orderBy('id', 'desc');

        return match ($order) {
            ProductOrderTypesEnum::OLDEST => $query->oldest('id'),
            ProductOrderTypesEnum::MOST_VIEWED => $this->_orderByMostViewed($query, config('visitor.table_name')),
            ProductOrderTypesEnum::MOST_DISCOUNT => $this->_orderByDiscount($query),
            ProductOrderTypesEnum::LEAST_EXPENSIVE => $this->_orderByPrice($query, 'asc'),
            ProductOrderTypesEnum::MOST_EXPENSIVE => $this->_orderByPrice($query, 'desc'),
            default => $query->latest('id'),
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
            ->whereHas('product', function ($q) {
                $q->published();
            })
            ->when($category, function (Builder $query, $category) {
                $query->whereHas('product.category', function ($q) use ($category) {
                    $q
                        ->where('id', $category)
                        ->whereRegex('ancestry', get_db_ancestry_regex_string($category));
                });
            })
            ->when($festival, function (Builder $query, $festival) {
                $query->whereHas('product.festivals', function ($q) use ($festival) {
                    $q
                        ->where('id', $festival)
                        ->published();
                });
            });
    }

    //-------------------------------------------------------------------
    // Product ordering criteria
    //-------------------------------------------------------------------

    /**
     * @param Builder $query
     * @param string $visitableTable
     * @return Builder
     */
    private function _orderByMostViewed(Builder $query, string $visitableTable): Builder
    {
        return $query->select(['products.*', 'subquery.unique_visit_count'])
            ->joinSub(
                $this->_getVisitCountSubquery($visitableTable),
                'subquery',
                'products.id',
                '=',
                'subquery.id'
            )
            ->orderBy('unique_visit_count', 'desc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    private function _orderByDiscount(Builder $query): Builder
    {
        return $query->orderBy('is_available', 'asc')
            ->whereHas('items', function ($q) {
                $q
                    ->orderBy('is_available', 'asc')
                    ->orderByRaw($this->_getDiscountOrderClause() . ' desc')
                    ->orderByRaw($this->_getDiscountOrderReverseClause() . ' asc');
            });
    }

    /**
     * @param Builder $query
     * @param string $orderDirection
     * @return Builder
     */
    private function _orderByPrice(Builder $query, string $orderDirection): Builder
    {
        return $query->whereHas('items', function ($q) use ($orderDirection) {
            $q
                ->orderBy('is_available', 'asc')
                ->orderByRaw($this->_getPriceOrderClause() . ' ' . $orderDirection);
        });
    }

    /**
     * @param string $visitableTable
     * @return \Illuminate\Database\Query\Builder
     */
    private function _getVisitCountSubquery(string $visitableTable): \Illuminate\Database\Query\Builder
    {
        return DB::table('products')
            ->select('products.id')
            ->selectRaw('COUNT(DISTINCT ' . $visitableTable . '.ip) AS unique_visit_count')
            ->leftJoin(
                $visitableTable,
                function ($join) use ($visitableTable) {
                    $join->on('products.id', $visitableTable . '.visitable_id')
                        ->where('visitable_type', Product::class);
                }
            )
            ->groupBy('products.id');
    }

    /**
     * @return string
     */
    private function _getDiscountOrderClause(): string
    {
        return (new CaseWhen())
            ->when('((discounted_from IS NULL AND discounted_until IS NOT NULL AND discounted_until >= UNIX_TIMESTAMP()) OR ' .
                '(discounted_from IS NOT NULL AND discounted_until IS NULL  AND discounted_from <= UNIX_TIMESTAMP()) OR ' .
                '(discounted_from IS NOT NULL AND discounted_until IS NOT NULL AND discounted_from <= UNIX_TIMESTAMP() AND discounted_until >= UNIX_TIMESTAMP())' .
                ') AND (stock_count > 0 AND discounted_price IS NOT NULL)', '((price - discounted_price) / price * 100)')
            ->build(null)['statement'];
    }

    /**
     * @return string
     */
    private function _getDiscountOrderReverseClause(): string
    {
        return (new CaseWhen())
            ->when('discounted_from IS NULL AND discounted_until IS NULL ' .
                'AND discounted_price IS NOT NULL AND stock_count > 0', 'discounted_price')
            ->else('price', [], null)['statement'];
    }

    /**
     * @return string
     */
    private function _getPriceOrderClause(): string
    {
        return (new CaseWhen())
            ->when('((discounted_from IS NULL AND discounted_until IS NOT NULL AND discounted_until >= UNIX_TIMESTAMP()) OR ' .
                '(discounted_from IS NOT NULL AND discounted_until IS NULL AND discounted_from <= UNIX_TIMESTAMP()) OR ' .
                '(discounted_from IS NOT NULL AND discounted_until IS NOT NULL AND discounted_from <= UNIX_TIMESTAMP() AND discounted_until >= UNIX_TIMESTAMP())' .
                ') AND (stock_count > 0 AND discounted_price IS NOT NULL)', 'discounted_price')
            ->else('price', [], null)['statement'];
    }
}
