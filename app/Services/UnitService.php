<?php

namespace App\Services;

use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Services\Contracts\UnitServiceInterface;
use App\Support\Service;

class UnitService extends Service implements UnitServiceInterface
{
    public function __construct(protected UnitRepositoryInterface $repository)
    {

    }
}
