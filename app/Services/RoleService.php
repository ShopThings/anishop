<?php

namespace App\Services;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Gates\RolesEnum;
use App\Services\Contracts\RoleServiceInterface;
use App\Support\Gate\PermissionHelper;
use App\Support\Traits\ServiceTrait;
use Illuminate\Support\Facades\Auth;

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
        //
        if ($user->hasRole(RolesEnum::DEVELOPER->value))
            return array_map(fn($item) => [
                'name' => RolesEnum::getTranslations($item->value),
                'value' => $item->value
            ], RolesEnum::cases());
        return array_map(fn($item) => [
            'name' => RolesEnum::getTranslations($item->value),
            'value' => $item->value
        ], RolesEnum::getAssignableRoles());
    }
}
