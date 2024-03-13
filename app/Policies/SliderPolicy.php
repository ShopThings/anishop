<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Slider;
use App\Support\Traits\PolicyTrait;

class SliderPolicy
{
    use PolicyTrait;

    protected string $modelClass = Slider::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::SLIDER;
}
