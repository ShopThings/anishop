<?php

namespace App\Repositories;

use App\Models\ProductAttributeValue;
use App\Repositories\Contracts\ProductAttributeValueRepositoryInterface;
use App\Support\Repository;

class ProductAttributeValueRepository extends Repository implements ProductAttributeValueRepositoryInterface
{
    protected bool $useSoftDeletes = false;

    public function __construct(ProductAttributeValue $model)
    {
        parent::__construct($model);
    }
}
