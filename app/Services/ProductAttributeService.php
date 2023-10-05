<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeRepositoryInterface;
use App\Services\Contracts\ProductAttributeServiceInterface;
use App\Support\Service;

class ProductAttributeService extends Service implements ProductAttributeServiceInterface
{
    public function __construct(protected ProductAttributeRepositoryInterface $repository)
    {

    }
}
