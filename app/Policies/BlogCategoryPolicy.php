<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\BlogCategory;
use App\Support\Traits\PolicyTrait;

class BlogCategoryPolicy
{
    use PolicyTrait;

    protected string $modelClass = BlogCategory::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::BLOG_CATEGORY;
}
