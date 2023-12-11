<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Support\Traits\PolicyTrait;

class SmsLogPolicy
{
    use PolicyTrait;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::SMS_LOG;

    protected array $only = ['viewAny'];
}
