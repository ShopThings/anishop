<?php

namespace App\Policies;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Models\CategoryImage;
use App\Support\Traits\PolicyTrait;

class CategoryImagePolicy
{
    use PolicyTrait;

    protected string $modelClass = CategoryImage::class;

    protected PermissionPlacesEnum $permissionPlace = PermissionPlacesEnum::CATEGORY_IMAGE;

    protected array $except = ['batchDelete'];

    public function __construct()
    {
        $this->checkIsDeletable = false;
    }
}
