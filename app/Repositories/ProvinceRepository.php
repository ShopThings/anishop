<?php

namespace App\Repositories;

use App\Models\Province;
use App\Repositories\Contracts\ProvinceRepositoryInterface;
use App\Support\Repository;

class ProvinceRepository extends Repository implements ProvinceRepositoryInterface
{
    public function __construct(Province $model)
    {
        parent::__construct($model);
    }
}
