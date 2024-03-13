<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\User;
use App\Models\FileManager;
use App\Support\Gate\PermissionHelper;
use App\Support\Traits\PolicyTrait;

class FilePolicy
{
    use PolicyTrait;

    protected string $modelClass = FileManager::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::FILE_MANAGER;

    protected array $except = ['view', 'forceDelete'];

    public function __construct()
    {
        $this->checkCreator = false;
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
