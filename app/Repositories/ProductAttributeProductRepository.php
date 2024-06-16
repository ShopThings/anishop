<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductAttributeCategory;
use App\Models\ProductAttributeProduct;
use App\Repositories\Contracts\ProductAttributeProductRepositoryInterface;
use App\Support\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductAttributeProductRepository extends Repository implements ProductAttributeProductRepositoryInterface
{
    protected bool $useSoftDeletes = false;

    public function __construct(
        ProductAttributeProduct            $model,
        protected ProductAttributeCategory $productAttributeCategoryModel,
        protected ProductAttributeProduct $productAttributeProductModel,
        protected Product                  $productModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getProductAttributes(int $productId): Collection|null
    {
        $query = $this->productModel->newQuery()
            ->with('productAttrValues')
            ->where('id', $productId);

        $product = $query->first();

        if (!$product instanceof Model) return null;

        return $product
            ->productAttrValues()
            ->with('attrValue')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function modifyProductAttributes(int $productId, array $attributeValues): bool
    {
        DB::beginTransaction();

        // first delete all attribute values that not exists in $attributeValues variable from db
        $this->productAttributeProductModel->newQuery()
            ->where('product_id', $productId)
            ->whereNotIn('product_attribute_value_id', $attributeValues)
            ->delete();

        // then update/create provided attribute values
        foreach ($attributeValues as $value) {
            $model = $this->productAttributeProductModel::updateOrCreate([
                'product_id' => $productId,
                'product_attribute_value_id' => $value,
            ]);

            if (!$model instanceof Model) {
                DB::rollBack();
                return false;
            }
        }

        DB::commit();
        return true;
    }
}
