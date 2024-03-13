<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Order;
use App\Support\Traits\PolicyTrait;

class OrderPolicy
{
    use PolicyTrait;

    protected string $modelClass = Order::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::ORDER;

    protected array $only = ['update'];
}
