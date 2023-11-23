<?php

namespace App\Repositories;

use App\Models\BlogCommentBadge;
use App\Repositories\Contracts\BlogBadgeRepositoryInterface;
use App\Support\Repository;

class BlogBadgeRepository extends Repository implements BlogBadgeRepositoryInterface
{
    public function __construct(BlogCommentBadge $model)
    {
        parent::__construct($model);
    }
}
