<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeCategoryRepositoryInterface;
use App\Services\Contracts\ProductAttributeCategoryServiceInterface;
use App\Support\Service;

class ProductAttributeCategoryService extends Service implements ProductAttributeCategoryServiceInterface
{
    public function __construct(protected ProductAttributeCategoryRepositoryInterface $repository)
    {

    }
}
