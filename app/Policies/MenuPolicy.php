<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Menu;
use App\Support\Traits\PolicyTrait;

class MenuPolicy
{
    use PolicyTrait;

    protected string $modelClass = Menu::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::MENU;

    protected array $only = ['viewAny', 'view'];
}
