<?php

namespace App\Http\Resources\User;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserContactUsSingleResource extends JsonResource
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
            'title' => $this->title,
            'message' => $this->message,
            'answer' => $this->answer,
            'is_seen' => $this->is_seen,
            'answered_at' => $this->answered_at
                ? vertaTz($this->answered_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
        ];
    }
}
