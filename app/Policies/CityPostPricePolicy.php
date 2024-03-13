<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\CityPostPrice;
use App\Support\Traits\PolicyTrait;

class CityPostPricePolicy
{
    use PolicyTrait;

    protected string $modelClass = CityPostPrice::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::CITY_POST_PRICE;

    protected array $except = ['forceDelete'];

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
