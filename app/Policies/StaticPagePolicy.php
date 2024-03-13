<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\StaticPage;
use App\Support\Traits\PolicyTrait;

class StaticPagePolicy
{
    use PolicyTrait;

    protected string $modelClass = StaticPage::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::STATIC_PAGE;
}
