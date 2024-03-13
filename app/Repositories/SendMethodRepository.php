<?php

namespace App\Repositories;

use App\Models\SendMethod;
use App\Repositories\Contracts\SendMethodRepositoryInterface;
use App\Support\Repository;

class SendMethodRepository extends Repository implements SendMethodRepositoryInterface
{
    public function __construct(SendMethod $model)
    {
        parent::__construct($model);
    }
}
