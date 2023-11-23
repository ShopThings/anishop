<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Support\Repository;

class UnitRepository extends Repository implements UnitRepositoryInterface
{
    public function __construct(Unit $model)
    {
        parent::__construct($model);
    }
}
