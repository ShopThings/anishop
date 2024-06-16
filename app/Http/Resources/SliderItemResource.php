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
        $this->resource->load('slider');
        $this->resource->load('creator');

        return [
            'id' => $this->id,
            'slider' => new SliderShowResource($this->slider),
            'priority' => $this->priority,
            'options' => $this->options,
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
