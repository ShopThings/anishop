<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeProductRepositoryInterface;
use App\Services\Contracts\ProductAttributeProductServiceInterface;
use App\Support\Service;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeProductService extends Service implements ProductAttributeProductServiceInterface
{
    public function __construct(
        protected ProductAttributeProductRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getProductAttributes(int $productId): Collection|Model|null
    {
        return $this->repository->getProductAttributes($productId);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'product_attribute_value_id' => $attributes['product_attribute_value'],
            'product_id' => $attributes['product'],
        ];

        return $this->repository->create($attrs);
    }
}
