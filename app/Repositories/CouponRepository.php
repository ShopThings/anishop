<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Support\Repository;

class CouponRepository extends Repository implements CouponRepositoryInterface
{
    public function __construct(Coupon $model)
    {
        parent::__construct($model);
    }
}
