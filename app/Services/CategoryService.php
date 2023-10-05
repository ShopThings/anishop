<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Support\Service;

class CategoryService extends Service implements CategoryServiceInterface
{
    public function __construct(protected CategoryRepositoryInterface $repository)
    {

    }
}
