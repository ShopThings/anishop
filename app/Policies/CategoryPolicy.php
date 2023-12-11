<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Category;
use App\Support\Traits\PolicyTrait;

class CategoryPolicy
{
    use PolicyTrait;

    protected string $modelClass = Category::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::CATEGORY;
}
