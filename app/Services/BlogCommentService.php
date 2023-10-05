<?php

namespace App\Services;

use App\Repositories\Contracts\BlogCommentRepositoryInterface;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Support\Service;

class BlogCommentService extends Service implements BlogCommentServiceInterface
{
    public function __construct(protected BlogCommentRepositoryInterface $repository)
    {

    }
}
