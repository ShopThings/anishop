<?php

namespace App\Http\Resources\Home;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\BlogCategoryShowResource;
use App\Http\Resources\Showing\ImageShowResource;
use App\Http\Resources\Showing\UserBlogShowResource;
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
            'category' => new BlogCategoryShowResource($this->category),
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => new ImageShowResource($this->image),
            'created_by' => $this->created_by ? new UserBlogShowResource($this->creator) : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT->value)
            ),
        ];
    }
}
