<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\OrderDetail;
use App\Support\Traits\PolicyTrait;

class OrderDetailPolicy
{
    use PolicyTrait;

    protected string $modelClass = OrderDetail::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::ORDER;

    protected array $except = ['restore', 'forceDelete', 'batchDelete'];
}
