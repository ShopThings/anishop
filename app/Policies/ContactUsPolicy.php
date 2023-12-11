<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\ContactUs;
use App\Support\Traits\PolicyTrait;

class ContactUsPolicy
{
    use PolicyTrait;

    protected string $modelClass = ContactUs::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::CONTACT_US;

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
