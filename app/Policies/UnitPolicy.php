<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Unit;
use App\Support\Traits\PolicyTrait;

class UnitPolicy
{
    use PolicyTrait;

    protected string $modelClass = Unit::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::UNIT;
}
