<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Blog;
use App\Models\User;
use App\Support\Traits\PolicyTrait;

class BlogPolicy
{
    use PolicyTrait;

    protected string $modelClass = Blog::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::BLOG;

    public function __construcy()
    {
        $this->checkIsDeletable = false;
    }

    public function reportComment(User $user, Blog $model)
    {
        return $model->is_published;
    }
}
