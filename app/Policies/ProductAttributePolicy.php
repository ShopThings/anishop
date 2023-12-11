<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\ProductAttribute;
use App\Support\Traits\PolicyTrait;

class ProductAttributePolicy
{
    use PolicyTrait;

    protected string $modelClass = ProductAttribute::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PRODUCT_ATTRIBUTE;

    protected array $except = ['restore', 'forceDelete'];

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
