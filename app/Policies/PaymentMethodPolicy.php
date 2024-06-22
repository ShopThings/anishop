<?php

namespace App\Policies;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Gates\RolesEnum;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
use App\Support\Traits\PolicyTrait;
use Illuminate\Database\Eloquent\Collection;

class PaymentMethodPolicy
{
    use PolicyTrait;

    protected string $modelClass = PaymentMethod::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PAYMENT_METHOD;

    public function view(User $user, PaymentMethod $model): bool
    {
        if ($user->hasRole(RolesEnum::DEVELOPER->value)) return true;
        if ($user->id === $model->creator?->id) return true;

        return !$model->is_sealed && $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::READ,
                    $this->permissionPlace)
            );
    }

    public function update(User $user, PaymentMethod $model): bool
    {
        if ($user->hasRole(RolesEnum::DEVELOPER->value)) return true;
        if ($user->id === $model->creator?->id) return true;

        return !$model->is_sealed && $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::UPDATE,
                    $this->permissionPlace)
            );
    }

    public function delete(User $user, PaymentMethod $model): bool
    {
        if ($user->hasRole(RolesEnum::DEVELOPER->value)) return true;
        if ($user->id === $model->creator?->id) return true;

        return !$model->is_sealed && $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::DELETE,
                    $this->permissionPlace)
            );
    }

    public function forceDelete(User $user, PaymentMethod|Collection $model): bool
    {
        if ($user->hasRole(RolesEnum::DEVELOPER->value)) return true;

        if ($model instanceof PaymentMethod && $model->is_sealed) {
            return false;
        } elseif ($model instanceof Collection) {
            if ($model->filter(function ($item) {
                return $item?->is_sealed;
            })->isNotEmpty()) {
                return false;
            }
        }

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::PERMANENT_DELETE,
                PermissionPlacesEnum::PRODUCT_COMMENT)
        );
    }

    public function batchDelete(User $user, array $ids): bool
    {
        if (
            PaymentMethod::query()
                ->where('is_sealed', DatabaseEnum::DB_YES)
                ->whereIn('id', $ids)
                ->count()
        ) {
            return false;
        }

        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                $this->permissionPlace)
        );
    }
}
