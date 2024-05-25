<?php

namespace App\Policies;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Blog;
use App\Models\BlogComment;
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

    /**
     * @param User $user
     * @param Blog $model
     * @return bool
     */
    public function reportComment(User $user, Blog $model, BlogComment $comment): bool
    {
        return $model->is_published &&
            $model->is_commenting_allowed &&
            CommentConditionsEnum::ACCEPTED->value === $comment->condition;
    }

    /**
     * @param User $user
     * @param Blog $model
     * @return mixed
     */
    public function isPubliclyAccessible(User $user, Blog $model): bool
    {
        return $model->is_published;
    }
}
