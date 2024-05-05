<?php

namespace App\Http\Resources\Home;

use App\Enums\Blogs\BlogVotingTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\BlogCategoryShowResource;
use App\Http\Resources\Showing\ImageShowResource;
use App\Http\Resources\Showing\UserBlogShowResource;
use App\Models\BlogVote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $votingType = $this->getBlogVotingType($request);

        return [
            'id' => $this->id,
            'category' => new BlogCategoryShowResource($this->category),
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => new ImageShowResource($this->image),
            'brief_description' => $this->brief_description,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'is_commenting_allowed' => $this->is_commenting_allowed,
            'comment_counts' => $this->resource->commentsCount(),
            'view_counts' => $this->resource->uniqueIPViews(),
            'up_vote_counts' => $this->resource->upVoteCount(),
            'down_vote_counts' => $this->resource->downVoteCount(),
            'vote_type' => $votingType->value,
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

    /**
     * @param Request $request
     * @return BlogVotingTypesEnum
     */
    protected function getBlogVotingType(Request $request): BlogVotingTypesEnum
    {
        $user = $request->user();
        if (!$user?->id) return BlogVotingTypesEnum::NOT_SET;

        $voteRecord = BlogVote::query()
            ->where('blog_id', $this->id)
            ->where('user_id', $user->id)
            ->first(['is_voted']);

        if ($voteRecord['is_voted']) {
            return BlogVotingTypesEnum::VOTED;
        }
        return BlogVotingTypesEnum::NOT_VOTED;
    }
}
