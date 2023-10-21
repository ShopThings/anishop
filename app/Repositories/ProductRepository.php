<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductProperty;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
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
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator
    {
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
            });

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
}
