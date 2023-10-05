<?php

namespace App\Services;

use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Services\Contracts\BrandServiceInterface;
use App\Support\Service;

class BrandService extends Service implements BrandServiceInterface
{
    public function __construct(protected BrandRepositoryInterface $repository)
    {

    }
}
