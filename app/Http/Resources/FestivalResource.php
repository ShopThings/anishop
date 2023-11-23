<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FestivalResource extends JsonResource
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
            'slug' => $this->slug,
            'start_at' => $this->start_at
                ? verta($this->start_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'end_at' => $this->end_at
                ? verta($this->end_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'is_published' => $this->is_published,
            'created_by' => $this->when($this->created_by, $this->creator()),
            'updated_by' => $this->when($this->updated_by, $this->updater()),
            'deleted_by' => $this->when($this->deleted_by, $this->deleter()),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                verta($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->deleted_at,
                verta($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
