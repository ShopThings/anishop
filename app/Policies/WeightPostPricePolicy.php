<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\WeightPostPrice;
use App\Support\Traits\PolicyTrait;

class WeightPostPricePolicy
{
    use PolicyTrait;

    protected string $modelClass = WeightPostPrice::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::WEIGHT_POST_PRICE;

    protected array $except = ['restore', 'forceDelete'];

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
