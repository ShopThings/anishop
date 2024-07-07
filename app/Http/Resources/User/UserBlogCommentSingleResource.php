<?php

namespace App\Http\Resources\User;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\BlogBadgeShowResource;
use App\Http\Resources\Showing\BlogShowResource;
use App\Http\Resources\Showing\UserBlogShowResource;
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
        $this->resource->load('blog.image');
        $this->resource->load('badge');
        $this->resource->load('parent');

        $childrenCount = $this->resource->acceptedChildrenCount();
        $creator = $this->creator;

        return [
            'id' => $this->id,
            'blog' => new BlogShowResource($this->blog),
            'badge' => new BlogBadgeShowResource($this->badge),
            'parent' => new UserBlogCommentSingleResource($this->parent),
            'has_children' => $childrenCount > 0,
            'children_count' => $childrenCount,
            'is_for_current_user' => $this->isCommentForCurrentUser($creator),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition, 'نامشخص'),
                'value' => $this->condition->value,
            ],
            'description' => $this->description,
            'is_condition_changed' => $this->condition !== CommentConditionsEnum::UNSET->value,
            'created_by' => $this->created_by ? new UserBlogShowResource($creator) : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }

    /**
     * @param $creator
     * @return bool
     */
    protected function isCommentForCurrentUser($creator): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        return $creator->id === $user->id;
    }
}
