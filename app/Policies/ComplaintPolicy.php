<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\Complaint;
use App\Support\Traits\PolicyTrait;

class ComplaintPolicy
{
    use PolicyTrait;

    protected string $modelClass = Complaint::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::COMPLAINT;

    public function __constrcut()
    {
        $this->checkIsDeletable = false;
    }
}
