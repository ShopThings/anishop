<?php

namespace App\Services;

use App\Repositories\Contracts\SliderRepositoryInterface;
use App\Services\Contracts\SliderServiceInterface;
use App\Support\Service;

class SliderService extends Service implements SliderServiceInterface
{
    public function __construct(protected SliderRepositoryInterface $repository)
    {

    }
}
