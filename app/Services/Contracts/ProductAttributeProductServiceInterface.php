<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductAttributeProductServiceInterface extends ServiceInterface
{
    /**
     * @param int $productId
     * @return Collection|Model|null
     */
    public function getProductAttributes(int $productId): Collection|Model|null;

    /**
     * @param int $productId
     * @param array $attributes
     * @return bool
     */
    public function modifyProductAttributes(int $productId, array $attributeValues): bool;
}
