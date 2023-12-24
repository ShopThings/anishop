<?php

namespace App\Http\Resources\User;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBlogCommentSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // preload needed relations
        $this->resource->load('blog');
        $this->resource->load('badge');
        $this->resource->load('parent');
        $this->resource->load('children');

        return [
            'id' => $this->id,
            'blog' => $this->blog,
            'badge' => $this->badge,
            'parent' => $this->parent,
            'children' => UserBlogCommentSingleResource::collection($this->whenCounted('children')),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition),
                'value' => $this->condition,
            ],
            'status' => [
                'text' => CommentStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
            'description' => $this->description,
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
