<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\CategoryShowResource;
use App\Http\Resources\Showing\ImageShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'parent' => new CategoryShowResource($this->whenLoaded('parent')),
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => new ImageShowResource($this->whenLoaded('image')),
            'ancestry' => $this->ancestry,
            'level' => $this->level,
            'priority' => $this->priority,
            'show_in_menu' => $this->show_in_menu,
            'show_in_search_side_menu' => $this->show_in_search_side_menu,
            'show_in_slider' => $this->show_in_slider,
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
