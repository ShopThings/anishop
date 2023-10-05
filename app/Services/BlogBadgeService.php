<?php

namespace App\Services;

use App\Repositories\Contracts\BlogBadgeRepositoryInterface;
use App\Services\Contracts\BlogBadgeServiceInterface;
use App\Support\Service;

class BlogBadgeService extends Service implements BlogBadgeServiceInterface
{
    public function __construct(protected BlogBadgeRepositoryInterface $repository)
    {

    }
}
