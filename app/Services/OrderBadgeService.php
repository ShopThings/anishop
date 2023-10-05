<?php

namespace App\Services;

use App\Repositories\Contracts\OrderBadgeRepositoryInterface;
use App\Services\Contracts\OrderBadgeServiceInterface;
use App\Support\Service;

class OrderBadgeService extends Service implements OrderBadgeServiceInterface
{
    public function __construct(protected OrderBadgeRepositoryInterface $repository)
    {

    }
}
