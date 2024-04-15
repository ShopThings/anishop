<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Gates\RolesEnum;
use App\Models\User;
use App\Support\Gate\PermissionHelper;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function canReport(User $user): bool
    {
        return $user->hasAnyRole([
                RolesEnum::DEVELOPER->value,
                RolesEnum::SUPER_ADMIN->value,
            ]) ||
            $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::EXPORT,
                    PermissionPlacesEnum::USER)
            );
    }
}
