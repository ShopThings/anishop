<?php

namespace App\Support\Gate;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Gates\RolesEnum;

class PermissionHelper
{
    /**
     * @param array|RolesEnum $roles
     * @param string|null $model
     * @return string
     */
    public static function roleMiddleware(array|RolesEnum $roles, ?string $model = null): string
    {
        if (!is_array($roles)) $roles = [$roles];
        $arrRoles = array_map(function ($role) {
            if ($role instanceof RolesEnum)
                return $role->value;
            return $role;
        }, $roles);

        return 'role:' . implode('|', $arrRoles) . ($model ? ',' . $model : '');
    }

    /**
     * @param array|PermissionsEnum $permissions
     * @param string|null $model
     * @return string
     */
    public static function permissionMiddleware(array|PermissionsEnum $permissions, ?string $model = null): string
    {
        if (!is_array($permissions)) $permissions = [$permissions];
        $arrPerms = array_map(function ($perm) {
            if ($perm instanceof PermissionsEnum)
                return $perm->value;
            return $perm;
        }, $permissions);

        return 'permission:' . implode('|', $arrPerms) . ($model ? ',' . $model : '');
    }

    /**
     * @param array|RolesEnum|PermissionsEnum $rolesOrPermissions
     * @param string|null $model
     * @return string
     */
    public static function roleOrPermissionMiddleware(
        array|RolesEnum|PermissionsEnum $rolesOrPermissions,
        ?string                         $model = null
    ): string
    {
        if (!is_array($rolesOrPermissions)) $rolesOrPermissions = [$rolesOrPermissions];
        $arrRoleOrPerm = array_map(function ($roleOrPerm) {
            if ($roleOrPerm instanceof RolesEnum || $roleOrPerm instanceof PermissionsEnum)
                return $roleOrPerm->value;
            return $roleOrPerm;
        }, $rolesOrPermissions);

        return 'role_or_permission:' . implode('|', $arrRoleOrPerm) . ($model ? ',' . $model : '');
    }

    /**
     * @param PermissionsEnum $permission
     * @param PermissionPlacesEnum $on
     * @param string|null $model
     * @return string
     */
    public static function canMiddleware(
        PermissionsEnum      $permission,
        PermissionPlacesEnum $on,
        ?string              $model = null
    ): string
    {
        return 'can:' . $on->value . '.' . $permission->value . ($model ? ',' . $model : '');
    }

    /**
     * @param PermissionsEnum $permission
     * @param PermissionPlacesEnum $on
     * @return string
     */
    public static function permission(PermissionsEnum $permission, PermissionPlacesEnum $on): string
    {
        return $on->value . '.' . $permission->value;
    }
}
