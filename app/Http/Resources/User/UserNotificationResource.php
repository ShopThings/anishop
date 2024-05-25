<?php

namespace App\Http\Resources\User;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->data,
            'read_at' => $this->read_at
                ? vertaTz($this->read_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::NOTIFICATION_DEFAULT->value)
                : null,
        ];
    }
}
