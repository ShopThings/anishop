<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\User;
use App\Models\WeightPostPrice;
use App\Support\Gate\PermissionHelper;

class WeightPostPricePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::WEIGHT_POST_PRICE)
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WeightPostPrice $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::WEIGHT_POST_PRICE)
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
                PermissionPlacesEnum::WEIGHT_POST_PRICE)
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WeightPostPrice $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::WEIGHT_POST_PRICE)
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WeightPostPrice $model): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::WEIGHT_POST_PRICE)
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
                PermissionPlacesEnum::WEIGHT_POST_PRICE)
        );
    }
}
