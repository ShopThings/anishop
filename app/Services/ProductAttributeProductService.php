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
    public function modifyProductAttributes(int $productId, array $attributeValues): bool
    {
        return $this->repository->modifyProductAttributes($productId, $attributeValues);
    }
}
