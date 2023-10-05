<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Support\Service;

class ProductService extends Service implements ProductServiceInterface
{
    public function __construct(protected ProductRepositoryInterface $repository)
    {

    }
}
