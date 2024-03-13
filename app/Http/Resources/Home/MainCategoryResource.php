<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('children');

        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'parent' => new MainCategoryResource($this->whenLoaded('parent')),
            'children' => MainCategoryResource::collection($this->whenLoaded('children', function () {
                return $this->children
                    ->sortBy('priority')
                    ->sortBy('id');
            })),
            'name' => $this->name,
            'slug' => $this->slug,
            'level' => $this->level,
            'priority' => $this->priority,
        ];
    }
}
