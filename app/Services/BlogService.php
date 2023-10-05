<?php

namespace App\Services;

use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Services\Contracts\BlogServiceInterface;
use App\Support\Service;

class BlogService extends Service implements BlogServiceInterface
{
    public function __construct(protected BlogRepositoryInterface $repository)
    {

    }
}
