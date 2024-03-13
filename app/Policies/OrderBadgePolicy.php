<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\OrderBadge;
use App\Support\Traits\PolicyTrait;

class OrderBadgePolicy
{
    use PolicyTrait;

    protected string $modelClass = OrderBadge::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::ORDER_BADGE;
}
