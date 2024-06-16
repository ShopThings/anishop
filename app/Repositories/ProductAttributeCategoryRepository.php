<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductAttributeCategory;
use App\Repositories\Contracts\ProductAttributeCategoryRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductAttributeCategoryRepository extends Repository implements ProductAttributeCategoryRepositoryInterface
{
    use RepositoryTrait;

    protected bool $useSoftDeletes = false;

    public function __construct(
        ProductAttributeCategory $model,
        protected Product        $productModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCategoriesSearchFilterPaginated(
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
            ->with(['productAttr', 'category'])
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query
                    ->when($filter->getRelationSearch(), function ($q) use ($search) {
                        $q
                            ->orWhereHas('productAttr', function ($q) use ($search) {
                                $q->whereLike('title', $search);
                            })
                            ->orWhereHas('category', function ($q) use ($search) {
                                $q->where(function ($q) use ($search) {
                                    $q->orWhereLike([
                                        'latin_name',
                                        'escaped_name',
                                    ], $search);
                                });
                            });
                    });
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getProductAttributeCategories(int $productId): Collection|null
    {
        $productCategory = $this->productModel->newQuery()
            ->where('id', $productId)
            ->first('category_id')?->category_id;

        if (!$productCategory) return null;

        $query = $this->model->newQuery();
        $query
            ->with([
                'productAttr.attrValues.productAttrValues.product' => function ($q) use ($productId) {
                    $q
                        ->with('productAttrValues.attrValue.productAttr.productAttrs')
                        ->where('id', $productId);
                },
            ])
            ->whereHas('productAttr.attrValues')
            ->where('category_id', $productCategory);

        return $query->get();
    }
}
