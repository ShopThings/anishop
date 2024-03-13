<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\BlogComment;
use App\Support\Traits\PolicyTrait;

class BlogCommentPolicy
{
    use PolicyTrait;

    protected string $modelClass = BlogComment::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::BLOG_COMMENT;

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
