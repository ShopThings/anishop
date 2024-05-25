<?php

namespace App\Http\Resources;

use App\Enums\Gates\RolesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSingleResource extends JsonResource
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
            'national_code' => $this->national_code,
            'sheba_number' => $this->sheba_number,
            'is_admin' => $this->is_admin,
            'is_banned' => $this->is_banned,
            'ban_desc' => $this->ban_desc,
            'verified_at' => $this->verified_at
                ? vertaTz($this->verified_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'is_deletable' => $this->is_deletable,
            'roles' => $this->whenLoaded(
                'roles',
                RolesEnum::getTranslations($this->getRoleNames()->toArray())
            ),
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'updated_by' => $this->when($this->updated_by, new UserShowResource($this->updater)),
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->deleted_at,
                vertaTz($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
