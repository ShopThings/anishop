<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\SliderShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderItemResource extends JsonResource
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
            'slider' => new SliderShowResource($this->whenLoaded('slider')),
            'priority' => $this->priority,
            'options' => $this->options,
            'is_published' => $this->is_published,
            'created_by' => new UserShowResource($this->when($this->created_by, $this->creator())),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
