<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductAttributeProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @return Collection|Model|null
     */
    public function getProductAttributes(int $productId): Collection|Model|null;
}
