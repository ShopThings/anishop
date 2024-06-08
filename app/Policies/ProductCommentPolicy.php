<?php

namespace App\Policies;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
use App\Support\Traits\PolicyTrait;
use Illuminate\Database\Eloquent\Collection;

class ProductCommentPolicy
{
    use PolicyTrait;

    protected string $modelClass = Comment::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PRODUCT_COMMENT;

    public function __construct()
    {
        $this->checkCreator = false;
        $this->checkIsDeletable = false;
    }

    public function view(User $user, Comment $model, Product $product): bool
    {
        if ($user->id === $model->creator?->id) return true;
        if ($model->product_id !== $product->id) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                $this->permissionPlace)
        );
    }

    public function create(User $user, Product $product): bool
    {
        return $product->is_commenting_allowed;
    }

    public function update(User $user, Comment $model, Product $product): bool
    {
        if ($user->id === $model->creator?->id) return true;
        if ($model->product_id !== $product->id) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                $this->permissionPlace)
        );
    }

    public function delete(User $user, Comment $model, Product $product): bool
    {
        if ($user->id === $model->creator?->id) return true;
        if ($model->product_id !== $product->id) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                $this->permissionPlace)
        );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment|Collection $model): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::PERMANENT_DELETE,
                PermissionPlacesEnum::PRODUCT_COMMENT)
        );
    }

    public function batchDelete(User $user, Product $product, array $ids): bool
    {
        $count = $product->comments()->whereIn('id', $ids)->count();

        if ($count !== count($ids)) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                $this->permissionPlace)
        );
    }

    /**
     * @param User $user
     * @param Comment $model
     * @param Product $product
     * @return bool
     */
    public function reportComment(User $user, Comment $model, Product $product): bool
    {
        return $product->is_published &&
            $product->is_commenting_allowed &&
            CommentConditionsEnum::ACCEPTED->value === $model->condition;
    }

    /**
     * A general purpose method to check for user operation (mostly used for client not admin)
     *
     * @param User $user
     * @param Comment $model
     * @param bool $checkIsAllowed
     * @return bool
     */
    public function canDoOperation(User $user, Comment $model, bool $checkIsAllowed = false): bool
    {
        if ($checkIsAllowed && !$model->product?->is_commenting_allowed) return false;

        return $user->id === $model->creator?->id;
    }
}
