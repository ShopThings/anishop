<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Festival;
use App\Support\Traits\PolicyTrait;

class FestivalPolicy
{
    use PolicyTrait;

    protected string $modelClass = Festival::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::FESTIVAL;

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
