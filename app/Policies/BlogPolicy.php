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

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }

    /**
     * @param User|null $user
     * @param Blog $model
     * @return mixed
     */
    public function isPubliclyAccessible(?User $user, Blog $model): bool
    {
        return $model->is_published;
    }
}
