<?php

namespace App\Http\Resources;

use App\Enums\SMS\SMSSenderTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmsLogResource extends JsonResource
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
            'receiver_number' => $this->receiver_number,
            'panel_number' => $this->panel_number,
            'pane_name' => $this->pane_name,
            'body' => $this->body,
            'type' => SMSSenderTypesEnum::getTranslations($this->type),
            'sender' => SMSSenderTypesEnum::getTranslations($this->sender),
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
