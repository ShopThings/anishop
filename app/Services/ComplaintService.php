<?php

namespace App\Services;

use App\Repositories\Contracts\ComplaintRepositoryInterface;
use App\Services\Contracts\ComplaintServiceInterface;
use App\Support\Service;

class ComplaintService extends Service implements ComplaintServiceInterface
{
    public function __construct(protected ComplaintRepositoryInterface $repository)
    {

    }
}
