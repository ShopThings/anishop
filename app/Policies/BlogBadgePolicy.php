<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\BlogCommentBadge;
use App\Support\Traits\PolicyTrait;

class BlogBadgePolicy
{
    use PolicyTrait;

    protected string $modelClass = BlogCommentBadge::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::BLOG_COMMENT_BADGE;
}
