<?php

namespace App\Services;

use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Services\Contracts\MenuServiceInterface;
use App\Support\Service;

class MenuService extends Service implements MenuServiceInterface
{
    public function __construct(protected MenuRepositoryInterface $repository)
    {

    }
}
