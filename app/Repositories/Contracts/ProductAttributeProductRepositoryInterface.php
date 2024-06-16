<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

interface ProductAttributeProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @return Collection|null
     */
    public function getProductAttributes(int $productId): Collection|null;

    /**
     * @param int $productId
     * @param array $attributeValues
     * @return bool
     */
    public function modifyProductAttributes(int $productId, array $attributeValues): bool;
}
