<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\CategoryShowResource;
use App\Http\Resources\Showing\ImageShowInfoResource;
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
            'parent' => $this->when($this->parent_id, new CategoryShowResource($this->parent)),
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->when(
                $this->whenLoaded('categoryImage') &&
                $this->categoryImage?->relationLoaded('image'),
                function () {
                    return $this->categoryImage->image?->id
                        ? new ImageShowInfoResource($this->categoryImage->image)
                        : null;
                }
            ),
            'ancestry' => $this->ancestry,
            'level' => $this->level,
            'priority' => $this->priority,
            'show_in_menu' => $this->show_in_menu,
            'show_in_search_side_menu' => $this->show_in_search_side_menu,
            'show_in_slider' => $this->show_in_slider,
            'is_published' => $this->is_published,
            'is_deletable' => $this->is_deletable,
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'updated_by' => $this->when($this->updated_by, new UserShowResource($this->updater)),
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->deleted_at,
                vertaTz($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
