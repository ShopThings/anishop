<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'city_id' => $this->city_id,
            'province_id' => $this->province_id,
            'city' => $this->whenLoaded('city'),
            'province' => $this->whenLoaded('province'),
            'user' => $this->whenLoaded('user'),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                verta($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
