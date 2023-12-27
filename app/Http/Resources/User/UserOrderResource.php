<?php

namespace App\Http\Resources\User;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'send_status_title' => $this->send_status_title,
            'send_status_color_hex' => $this->send_status_color_hex,
            'final_price' => $this->final_price,
            'ordered_at' => $this->ordered_at
                ? verta($this->ordered_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
        ];
    }
}
