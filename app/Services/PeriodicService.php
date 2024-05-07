<?php

namespace App\Services;

use App\Repositories\Contracts\PeriodicRepositoryInterface;
use App\Services\Contracts\PeriodicServiceInterface;
use App\Support\Service;

class PeriodicService extends Service implements PeriodicServiceInterface
{
    public function __construct(
        protected PeriodicRepositoryInterface $repository
    )
    {
    }
}
