<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ImageShowInfoResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryImageItemResource extends JsonResource
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
            'name' => $this->name,
            'level' => $this->level,
            'slug' => $this->slug,
            'category_image_id' => $this->categoryImage?->id,
            'image' => $this->when(
                $this->whenLoaded('categoryImage') &&
                $this->categoryImage?->image,
                function () {
                    return $this->categoryImage->image?->id
                        ? new ImageShowInfoResource($this->categoryImage->image)
                        : null;
                }
            ),
            'created_by' => $this->categoryImage?->created_by
                ? new UserShowResource($this->categoryImage?->creator)
                : null,
            'updated_by' => $this->when(
                $this->categoryImage?->updated_by,
                new UserShowResource($this->categoryImage?->updater)
            ),
            'deleted_by' => $this->when(
                $this->categoryImage?->deleted_by,
                new UserShowResource($this->categoryImage?->deleter)
            ),
            'created_at' => $this->categoryImage?->created_at
                ? vertaTz($this->categoryImage?->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->categoryImage?->updated_at,
                vertaTz($this->categoryImage?->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->categoryImage?->deleted_at,
                vertaTz($this->categoryImage?->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
