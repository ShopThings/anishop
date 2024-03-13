<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Exceptions\NotDeletableException;
use App\Models\ReturnOrderRequest;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
use App\Support\Traits\PolicyTrait;

class ReturnOrderPolicy
{
    use PolicyTrait;

    protected string $modelClass = ReturnOrderRequest::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::RETURN_ORDER_REQUEST;

    protected array $except = ['batchDelete'];

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReturnOrderRequest $model): bool
    {
        if (!in_array($model->status, ReturnOrderStatusesEnum::getDeletableStatuses())) {
            throw new NotDeletableException('امکان حذف در این وضعیت مرجوعی، وجود ندارد.');
        }
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::DELETE,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        );
    }
}
