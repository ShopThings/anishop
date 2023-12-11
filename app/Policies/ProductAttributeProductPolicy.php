<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\ProductAttributeProduct;
use App\Support\Traits\PolicyTrait;

class ProductAttributeProductPolicy
{
    use PolicyTrait;

    protected string $modelClass = ProductAttributeProduct::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::PRODUCT_ATTRIBUTE;

    protected array $only = ['view', 'create'];

    public function __construct()
    {
        $this->checkCreator = false;
    }
}
