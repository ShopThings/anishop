<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Exceptions\NotDeletableException;
use App\Models\OrderDetail;
use App\Models\User;
use App\Support\Gate\PermissionHelper;

class OrderDetailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::ORDER)
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrderDetail $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::ORDER)
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
                PermissionPlacesEnum::ORDER)
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrderDetail $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::ORDER)
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderDetail $model): bool
    {
        if (!$model->is_deletable) {
            throw new NotDeletableException();
        }
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::ORDER)
        );
    }
}
