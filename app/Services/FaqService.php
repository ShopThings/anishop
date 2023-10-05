<?php

namespace App\Services;

use App\Repositories\Contracts\FaqRepositoryInterface;
use App\Services\Contracts\FaqServiceInterface;
use App\Support\Service;

class FaqService extends Service implements FaqServiceInterface
{
    public function __construct(protected FaqRepositoryInterface $repository)
    {

    }
}
