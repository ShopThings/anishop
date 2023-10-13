<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Festival;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
use Illuminate\Database\Eloquent\Collection;

class FestivalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::FESTIVAL)
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Festival $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::FESTIVAL)
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
                PermissionPlacesEnum::FESTIVAL)
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Festival $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::FESTIVAL)
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Festival $model): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::FESTIVAL)
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Festival $model): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::FESTIVAL)
        );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Festival|Collection $model): bool
    {
        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::PERMANENT_DELETE,
                PermissionPlacesEnum::FESTIVAL)
        )) {
            return true;
        } else {
            if ($model instanceof Festival) {
                if ($user->id === $model->creator()?->id)
                    return true;
            } else {
                $tmp = $model->filter(function ($item) use ($user) {
                    return isset($item->creator()->id) && $user->id !== $item->creator()->id;
                });

                if (!$tmp->count())
                    return true;
            }
            return false;
        }
    }

    /**
     * Determine whether the user can batch delete.
     */
    public function batchDelete(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::FESTIVAL)
        );
    }
}
