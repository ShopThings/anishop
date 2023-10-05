<?php

namespace App\Services;

use App\Repositories\Contracts\WeightPostPriceRepositoryInterface;
use App\Services\Contracts\WeightPostPriceServiceInterface;
use App\Support\Service;

class WeightPostPriceService extends Service implements WeightPostPriceServiceInterface
{
    public function __construct(protected WeightPostPriceRepositoryInterface $repository)
    {

    }
}
