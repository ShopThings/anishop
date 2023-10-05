<?php

namespace App\Services;

use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Services\Contracts\CouponServiceInterface;
use App\Support\Service;

class CouponService extends Service implements CouponServiceInterface
{
    public function __construct(protected CouponRepositoryInterface $repository)
    {

    }
}
