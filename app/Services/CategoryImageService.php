<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryImageRepositoryInterface;
use App\Services\Contracts\CategoryImageServiceInterface;
use App\Support\Service;

class CategoryImageService extends Service implements CategoryImageServiceInterface
{
    public function __construct(protected CategoryImageRepositoryInterface $repository)
    {

    }
}
