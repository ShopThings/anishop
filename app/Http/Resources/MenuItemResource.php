<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\MenuShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // need this to detect menu item in case of "id" changing
            'tmp_id' => $this->id,
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'menu' => new MenuShowResource($this->whenLoaded('menu')),
            'parent_id' => $this->parent_id,
            'parent' => new MenuItemResource($this->whenLoaded('parent')),
            'children' => new MenuItemResource($this->whenLoaded('children')),
            'title' => $this->title,
            'link' => $this->link,
            'priority' => $this->priority,
            'can_have_children' => $this->can_have_children,
            'is_published' => $this->is_published,
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
