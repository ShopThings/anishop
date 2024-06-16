<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeCategoryRepositoryInterface;
use App\Repositories\Contracts\ProductAttributeProductRepositoryInterface;
use App\Services\Contracts\ProductAttributeProductServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductAttributeProductService extends Service implements ProductAttributeProductServiceInterface
{
    public function __construct(
        protected ProductAttributeProductRepositoryInterface  $repository,
        protected ProductAttributeCategoryRepositoryInterface $productAttributeCategoryRepository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getProductAttributes(int $productId): Collection|null
    {
        return $this->repository->getProductAttributes($productId);
    }

    /**
     * @inheritDoc
     */
    public function modifyProductAttributes(Model $product, array $attributeValues): bool
    {
        $refinedAttributeValueIds = $this->getRefinedProductAttribute($product, $attributeValues);

        if (empty($refinedAttributeValueIds)) return true;

        return $this->repository->modifyProductAttributes($product->id, $refinedAttributeValueIds);
    }

    /**
     * @param Model $product
     * @param array $attributeValues
     * @return array
     */
    protected function getRefinedProductAttribute(Model $product, array $attributeValues): array
    {
        $attributeIds = array_keys($attributeValues);

        $where = new WhereBuilder();
        $where
            ->whereEqual('category_id', $product->category_id)
            ->whereIn('product_attribute_id', $attributeIds);

        $acceptableAttributeCategories = $this->productAttributeCategoryRepository->all(
            columns: ['product_attribute_id'],
            where: $where->build()
        )->pluck('product_attribute_id');

        if ($acceptableAttributeCategories->isEmpty()) return [];

        return array_map(
            fn($item) => $item['id'],
            array_filter(
                $attributeValues,
                fn($item) => in_array($item['product_attribute_id'], $acceptableAttributeCategories->toArray())
            )
        );
    }
}
