<?php

namespace App\Http\Resources\Showing;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnOrderShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserShowResource($this->whenLoaded('user')),
            'code' => $this->code,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status, 'نامشخص'),
                'value' => $this->status,
            ],
            'seen_status' => $this->seen_status,
            'requested_at' => $this->requested_at
                ? vertaTz($this->requested_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
