<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Enums\Products\ProductOrderTypesEnum;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Http\Requests\Filters\ProductFilter;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductProperty;
use App\Models\RelatedProduct;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Support\Filter;
use App\Support\Model\CaseWhen;
use App\Support\QB\ReportQueryAppenderTrait;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductRepository extends Repository implements ProductRepositoryInterface
{
    use RepositoryTrait,
        ReportQueryAppenderTrait;

    /**
     * @inheritDoc
     */
    protected function getMappedReportColumnToActualColumn(): array
    {
        return [
            'brand' => 'brand_id',
            'category' => 'category_id',
            'title' => 'title',
            'unit_name' => 'unit_name',
            'is_available' => 'is_available',
            'is_commenting_allowed' => 'is_commenting_allowed',
            'is_published' => 'is_published',
            'is_deleted' => 'deleted_at',
            //
            'color_name' => [
                'column' => 'color_name',
                'with' => 'items',
            ],
            'size' => [
                'column' => 'size',
                'with' => 'items',
            ],
            'guarantee' => [
                'column' => 'guarantee',
                'with' => 'items',
            ],
            'weight' => [
                'column' => 'weight',
                'with' => 'items',
            ],
            'price' => [
                'column' => 'price',
                'with' => 'items',
            ],
            'discounted_price' => [
                'column' => 'discounted_price',
                'with' => 'items',
            ],
            'discounted_from' => [
                'column' => 'discounted_from',
                'with' => 'items',
            ],
            'discounted_until' => [
                'column' => 'discounted_until',
                'with' => 'items',
            ],
            'tax_rate' => [
                'column' => 'tax_rate',
                'with' => 'items',
            ],
            'stock_count' => [
                'column' => 'stock_count',
                'with' => 'items',
            ],
            'max_cart_count' => [
                'column' => 'max_cart_count',
                'with' => 'items',
            ],
            'is_spacial' => [
                'column' => 'is_spacial',
                'with' => 'items',
            ],
            'is_each_available' => [
                'column' => 'is_available',
                'with' => 'items',
            ],
            'is_each_show_coming_soon' => [
                'column' => 'show_coming_soon',
                'with' => 'items',
            ],
            'is_each_show_call_for_more' => [
                'column' => 'show_call_for_more',
                'with' => 'items',
            ],
            'is_each_published' => [
                'column' => 'is_published',
                'with' => 'items',
            ],
            'has_separate_shipment' => [
                'column' => 'has_separate_shipment',
                'with' => 'items',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getSpecialReportColumns(): array
    {
        return [
            'is_deleted',
            //
            'color_name', 'size', 'guarantee', 'price', 'discounted_price',
            'stock_count', 'max_cart_count', 'weight', 'discounted_from',
            'discounted_until', 'tax_rate', 'is_spacial', 'is_each_available',
            'is_each_show_coming_soon', 'is_each_show_call_for_more',
            'is_each_published', 'has_separate_shipment',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getIsMultipleColumns(): array
    {
        return [
            'color_name', 'size', 'guarantee', 'price',
            'discounted_price', 'stock_count', 'max_cart_count',
            'weight', 'discounted_from', 'discounted_until', 'tax_rate',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getHasReplacementColumns(): array
    {
        return [
            'color_name', 'size', 'guarantee', 'price',
            'discounted_price', 'stock_count', 'max_cart_count',
            'weight', 'discounted_from', 'discounted_until', 'tax_rate',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getComparisonColumns(): array
    {
        return [
            'color_name', 'size', 'guarantee', 'price',
            'discounted_price', 'stock_count', 'max_cart_count',
            'weight', 'discounted_from', 'discounted_until', 'tax_rate',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getBetweenColumns(): array
    {
        return [
            'price', 'discounted_price', 'stock_count', 'max_cart_count',
            'weight', 'discounted_from', 'discounted_until', 'tax_rate',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getNullableColumns(): array
    {
        return [
            'color_name', 'size', 'guarantee', 'price',
            'discounted_price', 'stock_count', 'max_cart_count',
            'weight', 'discounted_from', 'discounted_until', 'tax_rate',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getSpecialBooleanColumns(): array
    {
        return ['is_deleted'];
    }

    /**
     * @inheritDoc
     */
    protected function getGeneralBooleanColumns(): array
    {
        return [
            'is_spacial', 'is_each_available', 'is_each_show_coming_soon',
            'is_each_show_call_for_more', 'is_each_published', 'has_separate_shipment'
        ];
    }

    public function __construct(
        Product                   $model,
        protected ProductProperty $productPropertyModel,
        protected ProductGallery  $productGalleryModel,
        protected RelatedProduct $relatedProductModel,
        protected Brand          $brandModel
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

        $order = [];
        if (!$filter instanceof HomeProductFilter) {
            $order = $filter->getOrder();
        }

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
                if (trim($where->getStatement()) !== '') {
                    $q->whereRaw($where->getStatement(), $where->getBindings());
                }
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

            $color = $filter->getColor();
            $size = $filter->getSize();
            $brand = $filter->getBrand();
            $colors = $filter->getColors();
            $sizes = $filter->getSizes();
            $brands = $filter->getBrands();
            $category = $filter->getCategory();
            $isSpecial = $filter->getIsSpecial();
            $isAvailable = $filter->getIsAvailable();
            $priceRange = $filter->getPriceRange();

            $query
                ->whereHas('items', function ($q) {
                    $q
                        ->published()
                        ->where(function ($q) {
                            $q
                                ->orWhereNotNull('color_name')
                                ->orWhereNotNull('size')
                                ->orWhereNotNull('guarantee')
                                ->orWhere('color_name', '<>', '')
                                ->orWhere('size', '<>', '')
                                ->orWhere('guarantee', '<>', '');
                        });
                })
                ->when($color, function (Builder $query, string $color) {
                    $query->whereHas('items', function ($q) use ($color) {
                        $q->where('color_name', $color);
                    });
                })
                ->when($size, function (Builder $query, string $size) {
                    $query->whereHas('items', function ($q) use ($size) {
                        $q->where('size', $size);
                    });
                })
                ->when($brand, function (Builder $query, int $brand) {
                    $query->where('brand_id', $brand);
                })
                ->when(count($colors), function (Builder $query) use ($colors) {
                    $query->whereHas('items', function ($q) use ($colors) {
                        $q->whereIn('color_name', $colors);
                    });
                })
                ->when(count($sizes), function (Builder $query) use ($sizes) {
                    $query->whereHas('items', function ($q) use ($sizes) {
                        $q->whereIn('size', $sizes);
                    });
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
                            ->where(function ($q) use ($priceRange) {
                                (new CaseWhen($q))
                                    // Check if discounted price is within the range
                                    ->when(
                                        '(discounted_from IS NULL AND discounted_until IS NOT NULL AND discounted_until >= ?) OR' .
                                        '(discounted_from IS NOT NULL AND discounted_until IS NULL AND discounted_from <= ?) OR' .
                                        '(discounted_from IS NOT NULL AND discounted_until IS NOT NULL AND discounted_from <= ? AND discounted_until >= ?)'
                                        , 'discounted_price >= ?', [now(), now(), now(), now(), $priceRange[0]])
                                    // If discounted conditions are not met, check the regular price
                                    ->else('price >= ?', [$priceRange[0]], 'where');
                            })
                            ->where(function ($q) use ($priceRange) {
                                (new CaseWhen($q))
                                    // Check if discounted price is within the range
                                    ->when(
                                        '(discounted_from IS NULL AND discounted_until IS NOT NULL AND discounted_until >= ?) OR' .
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
    public function getProductsFilterPaginatedForReport(
        Filter $filter = null,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator
    {
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query->with(['brand', 'category', 'image']);

        if (!empty($reportQuery)) {
            $query = $this->addToEloquentBuilder($query, $reportQuery);
        }

        return $this->_paginateWithOrder(query: $query, limit: $limit, page: $page, order: $order);
    }

    /**
     * @inheritDoc
     */
    public function getProductVariantByCode(string $code): ?Model
    {
        return $this->productPropertyModel->newQuery()
            ->where('code', $code)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getProductVariantsByCodes(array $codes): Collection
    {
        return $this->productPropertyModel->newQuery()
            ->whereIn('code', $codes)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getFilterBrands(HomeProductSideFilter $filter): Collection
    {
        $category = $filter->getCategory();
        $festival = $filter->getFestival();

        $query = $this->brandModel->published()->distinct();
        $query
            ->whereHas('products', function ($q) {
                $q->published();
            })
            ->whereHas('products.items', function ($q) {
                $q->published();
            })
            ->when($category, function (Builder $query, $category) {
                $query->whereHas('products.category', function ($q) use ($category) {
                    $q
                        ->where('id', $category)
                        ->whereRegex('ancestry', get_db_ancestry_regex_string($category));
                });
            })
            ->when($festival, function (Builder $query, $festival) {
                $query->whereHas('products.festivals.festival', function ($q) use ($festival) {
                    $q
                        ->where('id', $festival)
                        ->published()
                        ->activated();
                });
            });

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getFilterColorsAndSizes(HomeProductSideFilter $filter): Collection
    {
        $query = $this->productPropertyModel->published();
        $query = $this->_considerProductSideFilter($query, $filter);
        $query->where(function ($q) {
            $q->where('color_name', '<>', '')->whereNotNull('color_name');
        })->orWhere(function ($q) {
            $q->where('size', '<>', '')->whereNotNull('size');
        });

        return $query->get(['color_name as name', 'color_hex as hex', 'size']);
    }

    /**
     * @inheritDoc
     */
    public function getFilterPriceRange(HomeProductSideFilter $filter): array
    {
        $query = $this->productPropertyModel->published();
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

        $query = $this->model->published()
            // -productAttrValues is "product_attribute_products" table relation
            // -attrValues is "product_attribute_values" table relation
            ->withWhereHas('productAttrValues.attrValue', function ($q) use ($category) {
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
            ->with('productAttrValues.attrValue.productAttr');

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function createGallery(int $productId, array $images): bool
    {
        DB::beginTransaction();

        // first delete all images that not exists in $images variable from db
        $this->productGalleryModel->newQuery()
            ->where('product_id', $productId)
            ->whereNotIn('image_id', $images)
            ->delete();

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
        $this->relatedProductModel->newQuery()
            ->where('product_id', $productId)
            ->whereNotIn('related_id', $products)
            ->delete();

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
                    $modified->add($this->productPropertyModel::query()->find($product['id']));
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
                $query->whereHas('product.festivals.festival', function ($q) use ($festival) {
                    $q
                        ->where('id', $festival)
                        ->published()
                        ->activated();
                });
            });
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
        return $query->select('products.*')
            ->join(
                'product_properties',
                'products.id',
                '=',
                'product_properties.product_id'
            )
            ->orderBy('products.is_available', 'asc')
            ->orderBy('product_properties.is_available', 'asc')
            ->orderByRaw($this->_getDiscountOrderClause('product_properties') . ' desc')
            ->orderByRaw($this->_getDiscountOrderReverseClause('product_properties') . ' asc');
    }

    /**
     * @param Builder $query
     * @param string $orderDirection
     * @return Builder
     */
    private function _orderByPrice(Builder $query, string $orderDirection): Builder
    {
        return $query->select('products.*')
            ->join(
                'product_properties',
                'products.id',
                '=',
                'product_properties.product_id'
            )
            ->orderBy('products.is_available', 'asc')
            ->orderByRaw($this->_getPriceOrderClause('product_properties') . ' ' . $orderDirection);
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
     * @param string $table
     * @return string
     */
    private function _getDiscountOrderClause(string $table): string
    {
        return (new CaseWhen())
            ->when('((' . $table . '.discounted_from IS NULL AND ' . $table . '.discounted_until IS NOT NULL AND ' . $table . '.discounted_until >= UNIX_TIMESTAMP()) OR ' .
                '(' . $table . '.discounted_from IS NOT NULL AND ' . $table . '.discounted_until IS NULL AND ' . $table . '.discounted_from <= UNIX_TIMESTAMP()) OR ' .
                '(' . $table . '.discounted_from IS NOT NULL AND ' . $table . '.discounted_until IS NOT NULL AND ' . $table . '.discounted_from <= UNIX_TIMESTAMP() AND ' . $table . '.discounted_until >= UNIX_TIMESTAMP())' .
                ') AND (' . $table . '.stock_count > 0 AND ' . $table . '.discounted_price IS NOT NULL)', '((' . $table . '.price - ' . $table . '.discounted_price) / ' . $table . '.price * 100)')
            ->build(null)['statement'];
    }

    /**
     * @param string $table
     * @return string
     */
    private function _getDiscountOrderReverseClause(string $table): string
    {
        return (new CaseWhen())
            ->when($table . '.discounted_from IS NULL AND ' . $table . '.discounted_until IS NULL ' .
                'AND ' . $table . '.discounted_price IS NOT NULL AND ' . $table . '.stock_count > 0', $table . '.discounted_price')
            ->else($table . '.price', [], null)['statement'];
    }

    /**
     * @param string $table
     * @return string
     */
    private function _getPriceOrderClause(string $table): string
    {
        return (new CaseWhen())
            ->when('((' . $table . '.discounted_from IS NULL AND ' . $table . '.discounted_until IS NOT NULL AND ' . $table . '.discounted_until >= UNIX_TIMESTAMP()) OR ' .
                '(' . $table . '.discounted_from IS NOT NULL AND ' . $table . '.discounted_until IS NULL AND ' . $table . '.discounted_from <= UNIX_TIMESTAMP()) OR ' .
                '(' . $table . '.discounted_from IS NOT NULL AND ' . $table . '.discounted_until IS NOT NULL AND ' . $table . '.discounted_from <= UNIX_TIMESTAMP() AND ' . $table . '.discounted_until >= UNIX_TIMESTAMP())' .
                ') AND (' . $table . '.stock_count > 0 AND ' . $table . '.discounted_price IS NOT NULL)', $table . '.discounted_price')
            ->else($table . '.price', [], null)['statement'];
    }
}
