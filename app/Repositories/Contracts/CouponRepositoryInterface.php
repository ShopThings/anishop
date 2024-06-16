<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Results\CouponResultEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface CouponRepositoryInterface extends RepositoryInterface
{
    /**
     * @param string $code
     * @param User $user
     * @return Model|CouponResultEnum
     */
    public function checkCoupon(string $code, User $user): Model|CouponResultEnum;
}
