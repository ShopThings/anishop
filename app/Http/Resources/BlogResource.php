<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category'),
            'title' => $this->title,
            'escaped_title' => $this->escaped_title,
            'slug' => $this->slug,
            'image_id' => $this->image_id,
            'image' => $this->whenLoaded('image'),
            'description' => $this->description,
            'archive_tag' => $this->archive_tag,
            'keywords' => $this->keywords,
            'is_commenting_allowed' => $this->is_commenting_allowed,
            'is_published' => $this->is_published,
            'view_counts' => $this->whenCounted('views'),
            'vote_counts' => $this->whenCounted('votes'),
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
