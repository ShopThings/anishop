<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\ProductAttributeCategory;
use App\Models\User;
use App\Support\Gate\PermissionHelper;

class ProductAttributeCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::PRODUCT_ATTRIBUTE)
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductAttributeCategory $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::PRODUCT_ATTRIBUTE)
        );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::PRODUCT_ATTRIBUTE)
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductAttributeCategory $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::PRODUCT_ATTRIBUTE)
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductAttributeCategory $model): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::PRODUCT_ATTRIBUTE)
        );
    }

    /**
     * Determine whether the user can batch delete.
     */
    public function batchDelete(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::PRODUCT_ATTRIBUTE)
        );
    }
}
