<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogBadgeResource extends JsonResource
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
            'color_hex' => $this->color_hex,
            'is_starting_badge' => $this->is_starting_badge,
            'is_published' => $this->is_published,
            'is_deletable' => $this->is_deletable,
            'created_by' => new UserShowResource($this->when($this->created_by, $this->creator())),
            'updated_by' => new UserShowResource($this->when($this->updated_by, $this->updater())),
            'deleted_by' => new UserShowResource($this->when($this->deleted_by, $this->deleter())),
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
