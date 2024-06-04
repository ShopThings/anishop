<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductAttributeCategory;
use App\Models\ProductAttributeProduct;
use App\Models\ProductAttributeValue;
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
        protected ProductAttributeValue    $productAttributeValueModel,
        protected Product                  $productModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getProductAttributes(int $productId): Collection|Model|null
    {
        $query = $this->productAttributeCategoryModel->newQuery();
        $query
            ->with([
                'productAttr',
                'productAttr.attrValues',
                'productAttr.attrValues.productAttrValues',
            ])
            ->whereHas('productAttr.attrValues.productAttrValues.product', function (
                $query
            ) use ($productId) {
                $query->where(
                    'category_id',
                    $this->productModel->newQuery()
                        ->where('id', $productId)
                        ->first('category_id')?->category_id
                );
            });

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function modifyProductAttributes(int $productId, array $attributeValues): bool
    {
        DB::beginTransaction();

        // first delete all attribute values that not exists in $attributeValues variable from db
        $this->productAttributeValueModel->newQuery()
            ->where('product_id', $productId)
            ->whereNotIn('product_attribute_value_id', $attributeValues)
            ->delete();

        // then update/create provided attribute values
        foreach ($attributeValues as $value) {
            $model = $this->productAttributeValueModel::updateOrCreate([
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
