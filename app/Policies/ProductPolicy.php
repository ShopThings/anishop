<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Product;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
use App\Support\Traits\PolicyTrait;

class ProductPolicy
{
    use PolicyTrait;

    protected string $modelClass = Product::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PRODUCT;

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }

    /**
     * Determine whether the user can batch update.
     */
    public function batchUpdate(User $user): bool
    {
        return $user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::UPDATE,
                PermissionPlacesEnum::PRODUCT)
        );
    }
}
