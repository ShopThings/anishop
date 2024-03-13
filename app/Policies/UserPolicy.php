<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Gates\RolesEnum;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
use App\Support\Traits\PolicyTrait;

class UserPolicy
{
    use PolicyTrait;

    protected string $modelClass = User::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::USER;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($user->id === $model->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::USER)
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) return true;

        if (
            (
                !$user->hasRole(RolesEnum::DEVELOPER->value) &&
                $model->hasAnyRole([RolesEnum::DEVELOPER->value, RolesEnum::SUPER_ADMIN->value])
            )
            ||
            (
                $user->hasAnyRole([RolesEnum::ADMIN->value, RolesEnum::USER_MANAGER->value]) &&
                $model->hasRole(RolesEnum::ADMIN->value)
            )
        ) return false;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::USER)
        );
    }
}
