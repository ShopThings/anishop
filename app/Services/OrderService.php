<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Support\Service;

class OrderService extends Service implements OrderServiceInterface
{
    public function __construct(protected OrderRepositoryInterface $repository)
    {

    }
}
