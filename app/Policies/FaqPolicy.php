<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Faq;
use App\Support\Traits\PolicyTrait;

class FaqPolicy
{
    use PolicyTrait;

    protected string $modelClass = Faq::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::FAQ;

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
