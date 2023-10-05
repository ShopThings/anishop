<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeProductRepositoryInterface;
use App\Services\Contracts\ProductAttributeProductServiceInterface;
use App\Support\Service;

class ProductAttributeProductService extends Service implements ProductAttributeProductServiceInterface
{
    public function __construct(protected ProductAttributeProductRepositoryInterface $repository)
    {

    }
}
