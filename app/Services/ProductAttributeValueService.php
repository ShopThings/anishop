<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeValueRepositoryInterface;
use App\Services\Contracts\ProductAttributeValueServiceInterface;
use App\Support\Service;

class ProductAttributeValueService extends Service implements ProductAttributeValueServiceInterface
{
    public function __construct(protected ProductAttributeValueRepositoryInterface $repository)
    {

    }
}
