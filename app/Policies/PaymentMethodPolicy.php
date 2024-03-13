<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\PaymentMethod;
use App\Support\Traits\PolicyTrait;

class PaymentMethodPolicy
{
    use PolicyTrait;

    protected string $modelClass = PaymentMethod::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PAYMENT_METHOD;
}
