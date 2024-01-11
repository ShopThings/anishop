<?php

namespace App\Http\Resources\Showing;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentShowResource extends JsonResource
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
            'blog_id' => $this->blog_id,
            'blog' => new BlogShowResource($this->whenLoaded('blog')),
            'badge_id' => $this->badge_id,
            'badge' => new BlogBadgeShowResource($this->whenLoaded('badge')),
            'has_children' => $this->resource->hasChildren(),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition),
                'value' => $this->condition,
            ],
            'status' => [
                'text' => CommentStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
        ];
    }
}
