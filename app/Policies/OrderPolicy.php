<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Order;
use App\Models\User;
use App\Support\Gate\PermissionHelper;

class OrderPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::ORDER)
        );
    }
}
