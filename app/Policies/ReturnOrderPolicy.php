<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Models\ReturnOrderRequest;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
use Illuminate\Database\Eloquent\Collection;

class ReturnOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReturnOrderRequest $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
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
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReturnOrderRequest $model): bool
    {
        if ($user->id === $model->creator()?->id) return true;

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReturnOrderRequest $model): bool
    {
        if (!in_array($model->status, ReturnOrderStatusesEnum::getDeletableStatuses())) {
            throw new NotDeletableException('با توجه به وضعیت مرجوعی محصولات، امکان حذف وجود ندارد.');
        }
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReturnOrderRequest $model): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReturnOrderRequest|Collection $model): bool
    {
        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::PERMANENT_DELETE,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        )) {
            return true;
        } else {
            if ($model instanceof ReturnOrderRequest) {
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
}
