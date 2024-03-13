<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Coupon;
use App\Support\Traits\PolicyTrait;

class CouponPolicy
{
    use PolicyTrait;

    protected string $modelClass = Coupon::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::COUPON;

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
