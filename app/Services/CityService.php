<?php

namespace App\Services;

use App\Repositories\Contracts\CityRepositoryInterface;
use App\Services\Contracts\CityServiceInterface;
use App\Support\Service;

class CityService extends Service implements CityServiceInterface
{
    public function __construct(protected CityRepositoryInterface $repository)
    {

    }
}
