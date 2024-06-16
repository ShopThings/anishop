<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->send_status_code,
            'title' => $this->when($this->badge_title, $this->badge_title, $this->send_status_title),
            'color_hex' => $this->when($this->badge_color_hex, $this->badge_color_hex, $this->send_status_color_hex),
            'count' => $this->count,
        ];
    }
}
