<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Color;
use App\Support\Traits\PolicyTrait;

class ColorPolicy
{
    use PolicyTrait;

    protected string $modelClass = Color::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::COLOR;
}
