<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Service;

class ReportService extends Service implements ReportServiceInterface
{
    public function __construct(protected ReportRepositoryInterface $repository)
    {

    }
}
