<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductAttributeProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @return Collection|Model|null
     */
    public function getProductAttributes(int $productId): Collection|Model|null;

    /**
     * @param int $productId
     * @param array $attributeValues
     * @return bool
     */
    public function modifyProductAttributes(int $productId, array $attributeValues): bool;
}
