<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ProductAttributeProductServiceInterface extends ServiceInterface
{
    /**
     * @param int $productId
     * @return Collection|null
     */
    public function getProductAttributes(int $productId): Collection|null;

    /**
     * @param Model $product
     * @param array $attributeValues
     * @return bool
     */
    public function modifyProductAttributes(Model $product, array $attributeValues): bool;
}
