<?php

namespace App\Services;

use App\Repositories\Contracts\CityPostPriceRepositoryInterface;
use App\Services\Contracts\CityPostPriceServiceInterface;
use App\Support\Service;

class CityPostPriceService extends Service implements CityPostPriceServiceInterface
{
    public function __construct(protected CityPostPriceRepositoryInterface $repository)
    {

    }
}
