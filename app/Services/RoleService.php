<?php

namespace App\Services;

use App\Enums\Gates\RolesEnum;
use App\Services\Contracts\RoleServiceInterface;
use App\Support\Traits\ServiceTrait;
use Illuminate\Support\Facades\Auth;

class RoleService implements RoleServiceInterface
{
    use ServiceTrait;

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        $user = Auth::user();
        if (!$user) return [];
        //
        if ($user->hasRole(RolesEnum::DEVELOPER->value))
            return array_map(fn($item) => ['name' => $item->name, 'value' => $item->value], RolesEnum::cases());
        return array_map(fn($item) => ['name' => $item->name, 'value' => $item->value], RolesEnum::getAssignableRoles());
    }
}
