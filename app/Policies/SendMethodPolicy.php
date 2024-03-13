<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\SendMethod;
use App\Support\Traits\PolicyTrait;

class SendMethodPolicy
{
    use PolicyTrait;

    protected string $modelClass = SendMethod::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::SEND_METHOD;
}
