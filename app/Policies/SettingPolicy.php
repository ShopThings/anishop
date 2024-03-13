<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Setting;
use App\Support\Traits\PolicyTrait;

class SettingPolicy
{
    use PolicyTrait;

    protected string $modelClass = Setting::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::SETTING;

    protected array $only = ['viewAny', 'update'];

    public function __construct()
    {
        $this->checkCreator = false;
    }
}
