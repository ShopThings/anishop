<?php

namespace App\Services;

use App\Repositories\Contracts\FestivalRepositoryInterface;
use App\Services\Contracts\FestivalServiceInterface;
use App\Support\Service;

class FestivalService extends Service implements FestivalServiceInterface
{
    public function __construct(protected FestivalRepositoryInterface $repository)
    {

    }
}
