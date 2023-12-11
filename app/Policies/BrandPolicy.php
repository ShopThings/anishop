<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Brand;
use App\Support\Traits\PolicyTrait;

class BrandPolicy
{
    use PolicyTrait;

    protected string $modelClass = Brand::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::BRAND;
}
