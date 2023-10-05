<?php

namespace App\Services;

use App\Repositories\Contracts\ColorRepositoryInterface;
use App\Services\Contracts\ColorServiceInterface;
use App\Support\Service;

class ColorService extends Service implements ColorServiceInterface
{
    public function __construct(protected ColorRepositoryInterface $repository)
    {

    }
}
