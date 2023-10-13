<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductAttributeProductServiceInterface extends ServiceInterface
{
    /**
     * @param int $productId
     * @return Collection|Model|null
     */
    public function getProductAttributes(int $productId): Collection|Model|null;
}
