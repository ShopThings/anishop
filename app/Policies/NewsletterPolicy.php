<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Newsletter;
use App\Support\Traits\PolicyTrait;

class NewsletterPolicy
{
    use PolicyTrait;

    protected string $modelClass = Newsletter::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::NEWSLETTER;

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
