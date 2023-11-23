<?php

namespace App\Repositories;

use App\Models\WeightPostPrice;
use App\Repositories\Contracts\WeightPostPriceRepositoryInterface;
use App\Support\Repository;

class WeightPostPriceRepository extends Repository implements WeightPostPriceRepositoryInterface
{
    protected bool $useSoftDeletes = false;

    public function __construct(WeightPostPrice $model)
    {
        parent::__construct($model);
    }
}
