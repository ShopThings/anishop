<?php

namespace App\Http\Resources\Showing;

use App\Enums\Gates\RolesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'verified_at' => $this->verified_at
                ? vertaTz($this->verified_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'is_deletable' => $this->is_deletable,
            'roles' => $this->whenLoaded(
                'roles',
                RolesEnum::getTranslations($this->getRoleNames()->toArray())
            ),
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
