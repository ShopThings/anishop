<?php

namespace App\Services;

use App\Repositories\Contracts\ContactUsRepositoryInterface;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Support\Service;

class ContactUsService extends Service implements ContactUsServiceInterface
{
    public function __construct(protected ContactUsRepositoryInterface $repository)
    {

    }
}
