<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Comment;
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
}
