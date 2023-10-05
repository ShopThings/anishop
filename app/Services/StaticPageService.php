<?php

namespace App\Services;

use App\Repositories\Contracts\StaticPageRepositoryInterface;
use App\Services\Contracts\StaticPageServiceInterface;
use App\Support\Service;

class StaticPageService extends Service implements StaticPageServiceInterface
{
    public function __construct(protected StaticPageRepositoryInterface $repository)
    {

    }
}
