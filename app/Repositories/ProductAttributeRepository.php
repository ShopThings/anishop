<?php

namespace App\Repositories;

use App\Models\ProductAttribute;
use App\Repositories\Contracts\ProductAttributeRepositoryInterface;
use App\Support\Repository;

class ProductAttributeRepository extends Repository implements ProductAttributeRepositoryInterface
{
    protected bool $useSoftDeletes = false;

    public function __construct(ProductAttribute $model)
    {
        parent::__construct($model);
    }
}
