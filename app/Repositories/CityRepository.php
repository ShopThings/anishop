<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Support\Repository;

class CityRepository extends Repository implements CityRepositoryInterface
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
}
