<?php

namespace App\Policies;

use App\Enums\Gates\RolesEnum;
use App\Models\User;

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
        ]);
    }
}
