<?php

namespace App\Policies;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
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

    public function view(User $user, BlogComment $model, Blog $blog): bool
    {
        if ($user->id === $model->creator?->id) return true;
        if ($model->blog_id !== $blog->id) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                $this->permissionPlace)
        );
    }

    public function create(User $user, Blog $blog): bool
    {
        return $blog->is_commenting_allowed;
    }

    public function update(User $user, BlogComment $model, Blog $blog): bool
    {
        if ($user->id === $model->creator?->id) return true;
        if ($model->blog_id !== $blog->id) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                $this->permissionPlace)
        );
    }

    public function delete(User $user, BlogComment $model, Blog $blog): bool
    {
        if ($user->id === $model->creator?->id) return true;
        if ($model->blog_id !== $blog->id) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                $this->permissionPlace)
        );
    }

    public function batchDelete(User $user, Blog $blog, array $ids): bool
    {
        $count = $blog->comments()->whereIn('id', $ids)->count();

        if ($count !== count($ids)) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                $this->permissionPlace)
        );
    }

    /**
     * @param User $user
     * @param BlogComment $model
     * @param Blog $blog
     * @return bool
     */
    public function reportComment(User $user, BlogComment $model, Blog $blog): bool
    {
        return $blog->is_published &&
            $blog->is_commenting_allowed &&
            CommentConditionsEnum::ACCEPTED->value === $model->condition;
    }

    /**
     * A general purpose method to check for user operation (mostly used for client not admin)
     *
     * @param User $user
     * @param BlogComment $model
     * @param bool $checkIsAllowed
     * @return bool
     */
    public function canDoOperation(User $user, BlogComment $model, bool $checkIsAllowed = false): bool
    {
        if ($checkIsAllowed && !$model->blog?->is_commenting_allowed) return false;

        return $user->id === $model->creator?->id;
    }
}
