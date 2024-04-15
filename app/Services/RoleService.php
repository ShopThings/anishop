<?php

namespace App\Services;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Gates\RolesEnum;
use App\Models\User;
use App\Services\Contracts\RoleServiceInterface;
use App\Support\Gate\PermissionHelper;
use App\Support\Traits\ServiceTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class RoleService implements RoleServiceInterface
{
    use ServiceTrait;

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        $user = Auth::user();
        if (
            !$user ||
            !$user->hasPermissionTo(PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::USER)
            )
        ) return [];

        if ($user->hasRole(RolesEnum::DEVELOPER->value))
            return array_map(fn($item) => [
                'name' => RolesEnum::getTranslations($item->value, 'نامشخص'),
                'value' => $item->value,
            ], RolesEnum::cases());
        return array_map(fn($item) => [
            'name' => RolesEnum::getTranslations($item->value, 'نامشخص'),
            'value' => $item->value,
        ], RolesEnum::getAssignableRoles());
    }

    /**
     * @inheritDoc
     */
    public function getPermissions(): Collection
    {
        $user = Auth::user();
        if (
            !$user ||
            !$user->hasPermissionTo(PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::USER)
            )
        ) return collect();

        // I know it must be in another repository class but for now it is here
        return Permission::all(['id', 'name']);
    }

    /**
     * @inheritDoc
     */
    public function getUserPermissions(User $user): Collection
    {
        return $user->getAllPermissions()->pluck('name');
    }
}
