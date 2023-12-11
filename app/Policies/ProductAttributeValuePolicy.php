<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\ProductAttributeValue;
use App\Support\Traits\PolicyTrait;

class ProductAttributeValuePolicy
{
    use PolicyTrait;

    protected string $modelClass = ProductAttributeValue::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PRODUCT_ATTRIBUTE;

    protected array $except = ['restore', 'forceDelete'];

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
