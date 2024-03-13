<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\ProductAttributeCategory;
use App\Support\Traits\PolicyTrait;

class ProductAttributeCategoryPolicy
{
    use PolicyTrait;

    protected string $modelClass = ProductAttributeCategory::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PRODUCT_ATTRIBUTE;

    protected array $except = ['restore', 'forceDelete'];

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
