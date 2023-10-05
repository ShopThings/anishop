<?php

namespace App\Services;

use App\Repositories\Contracts\ProductCommentRepositoryInterface;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Support\Service;

class ProductCommentService extends Service implements ProductCommentServiceInterface
{
    public function __construct(protected ProductCommentRepositoryInterface $repository)
    {

    }
}
