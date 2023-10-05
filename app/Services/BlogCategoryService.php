<?php

namespace App\Services;

use App\Repositories\Contracts\BlogCategoryRepositoryInterface;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Support\Service;

class BlogCategoryService extends Service implements BlogCategoryServiceInterface
{
    public function __construct(protected BlogCategoryRepositoryInterface $repository)
    {

    }
}
