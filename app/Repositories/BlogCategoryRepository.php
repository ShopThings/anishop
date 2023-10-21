<?php

namespace App\Repositories;

use App\Models\BlogCategory;
use App\Repositories\Contracts\BlogCategoryRepositoryInterface;
use App\Support\Repository;

class BlogCategoryRepository extends Repository implements BlogCategoryRepositoryInterface
{
    public function __construct(BlogCategory $model)
    {
        parent::__construct($model);
    }
}
