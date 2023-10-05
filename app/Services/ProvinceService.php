<?php

namespace App\Services;

use App\Repositories\Contracts\ProvinceRepositoryInterface;
use App\Services\Contracts\ProvinceServiceInterface;
use App\Support\Service;

class ProvinceService extends Service implements ProvinceServiceInterface
{
    public function __construct(protected ProvinceRepositoryInterface $repository)
    {

    }
}
