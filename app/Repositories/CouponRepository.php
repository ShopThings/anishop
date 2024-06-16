<?php

namespace App\Repositories;

use App\Enums\Results\CouponResultEnum;
use App\Models\Coupon;
use App\Models\OrderDetail;
use App\Models\User;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Support\Repository;
use Illuminate\Database\Eloquent\Model;

class CouponRepository extends Repository implements CouponRepositoryInterface
{
    public function __construct(
        Coupon                $model,
        protected OrderDetail $orderDetailModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function checkCoupon(string $code, User $user): Model|CouponResultEnum
    {
        $coupon = $this->model->published()
            ->where('start_at', '>=', now())
            ->where('end_at', '<=', now())
            ->where('use_count', '>', 0)
            ->where('code', $code)
            ->first();

        if (!$coupon instanceof Model) return CouponResultEnum::NOT_FOUND;

        if ($coupon->reusable_after > 0) {
            $usedCount = $this->orderDetailModel->newQuery()
                ->where('user_id', $user->id)
                ->where('coupon_code', $code)
                ->whereBetween('ordered_at', [
                    now()->startOfDay()->subDays($coupon->reusable_after),
                    now()->endOfDay(),
                ])
                ->withAnyPaidOrder()
                ->count();

            if ($usedCount > $coupon->use_count) {
                return CouponResultEnum::LIMITED;
            }
        }

        return $coupon;
    }
}
