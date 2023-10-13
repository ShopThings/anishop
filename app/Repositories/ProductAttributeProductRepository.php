<?php

namespace App\Repositories;

use App\Models\ProductAttributeProduct;
use App\Repositories\Contracts\ProductAttributeProductRepositoryInterface;
use App\Support\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeProductRepository extends Repository implements ProductAttributeProductRepositoryInterface
{
    protected bool $useSoftDeletes = false;

    public function __construct(ProductAttributeProduct $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getProductAttributes(int $productId): Collection|Model|null
    {
        $query = $this->model->newQuery();
        $query
            ->where('product_id', $productId)
            ->withWhereHas('attrValues')
            ->withWhereHas('attrValues.productAttr');

        return $query->get();
    }
}
