<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Results\CouponResultEnum;
use App\Models\User;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CouponServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getCoupons(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param string $code
     * @param User $user
     * @return Model|CouponResultEnum
     */
    public function checkCoupon(string $code, User $user): Model|CouponResultEnum;
}
