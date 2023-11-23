<?php

namespace App\Repositories;

use App\Models\SmsLog;
use App\Repositories\Contracts\SmsLogRepositoryInterface;
use App\Support\Repository;

class SmsLogRepository extends Repository implements SmsLogRepositoryInterface
{
    public function __construct(SmsLog $model)
    {
        parent::__construct($model);
    }
}
