<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Exceptions\NotDeletableException;
use App\Models\User;
use App\Models\FileManager;
use App\Support\Gate\PermissionHelper;

class FilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::FILE_MANAGER)
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
                PermissionPlacesEnum::FILE_MANAGER)
        );
    }

    /**
     * Determine whether the user can update.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::FILE_MANAGER)
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FileManager $model): bool
    {
        if (!$model->is_deletable) {
            throw new NotDeletableException();
            return false;
        }
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::FILE_MANAGER)
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FileManager $model): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::FILE_MANAGER)
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
                PermissionPlacesEnum::FILE_MANAGER)
        );
    }

    /**
     * Determine whether the user can upload.
     */
    public function upload(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPLOAD,
                PermissionPlacesEnum::FILE_MANAGER)
        );
    }

    /**
     * Determine whether the user can download.
     */
    public function download(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DOWNLOAD,
                PermissionPlacesEnum::FILE_MANAGER)
        );
    }
}
