<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'group_name' => $this->group_name,
            'value' => $this->value,
            'min_value' => $this->min_value,
            'max_value' => $this->max_value,
        ];
    }
}
