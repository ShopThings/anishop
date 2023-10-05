<?php

namespace App\Services;

use App\Repositories\Contracts\ReturnOrderRepositoryInterface;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Support\Service;

class ReturnOrderService extends Service implements ReturnOrderServiceInterface
{
    public function __construct(protected ReturnOrderRepositoryInterface $repository)
    {

    }
}
