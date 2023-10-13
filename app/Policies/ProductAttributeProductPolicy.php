<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\ProductAttributeProduct;
use App\Models\User;
use App\Support\Gate\PermissionHelper;

class ProductAttributeProductPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductAttributeProduct $model): bool
    {
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
}
