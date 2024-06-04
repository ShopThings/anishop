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
use Illuminate\Support\Facades\Auth;

class ProductPolicy
{
    use PolicyTrait;

    protected string $modelClass = Product::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PRODUCT;

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }

    /**
     * Determine whether the user can batch update.
     */
    public function batchUpdate(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::PRODUCT)
        );
    }

    /**
     * @param User $user
     * @param Product $model
     * @param Comment $comment
     * @return bool
     */
    public function reportComment(User $user, Product $model, Comment $comment): bool
    {
        return $model->is_published &&
            $model->is_commenting_allowed &&
            CommentConditionsEnum::ACCEPTED->value === $comment->condition;
    }

    /**
     * @param User $user
     * @param Product $model
     * @return bool
     */
    public function voteComment(User $user, Product $model): bool
    {
        return Auth::check() && $model->is_commenting_allowed;
    }

    /**
     * @param User|null $user
     * @param Product $model
     * @return mixed
     */
    public function isPubliclyAccessible(?User $user, Product $model): bool
    {
        return $model->is_published;
    }
}
