<?php

namespace App\Http\Resources\Home;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentVotedTypesEnum;
use App\Enums\Comments\CommentVotingTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserCommentShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $votingType = $this->getVotingType($request, $this);

        return [
            'id' => $this->id,
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition, 'نامشخص'),
                'value' => $this->condition,
            ],
            'pros' => $this->pros,
            'cons' => $this->cons,
            'description' => $this->description,
            'up_vote_count' => $this->up_vote_count,
            'down_vote_count' => $this->down_vote_count,
            'vote_type' => $votingType->value,
            'created_by' => $this->created_by ? new UserCommentShowResource($this->creator) : null,
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
     * @return CommentVotedTypesEnum
     */
    protected function getVotingType(Request $request, $comment): CommentVotedTypesEnum
    {
        $user = $request->user();
        if (!$user?->id) return CommentVotedTypesEnum::NOT_SET;

        // check for previous vote footprint
        // NOTE: this is NOT so reliable, better way is to have separate table
        $cookieName = 'comment_vote_' . $comment->id . '_' . $request->user()->id;
        $cookieVal = $request->ip() . '_' . $request->user()->id;

        $commentCookie = Cookie::get($cookieName);
        if ($commentCookie && $commentCookie === $cookieVal) {
            $splatted = explode('_', $cookieVal);
            $val = Arr::last($splatted);

            if (is_null($val)) return CommentVotedTypesEnum::NOT_SET;

            $vote = CommentVotingTypesEnum::tryFrom($val);

            if (
                is_null($vote) ||
                in_array(
                    $vote->value,
                    [
                        CommentVotingTypesEnum::UNDO_LIKING->value,
                        CommentVotingTypesEnum::UNDO_DISLIKING->value,
                    ]
                )
            ) {
                return CommentVotedTypesEnum::NOT_SET;
            } elseif (in_array(
                $vote->value,
                [
                    CommentVotingTypesEnum::LIKING->value,
                    CommentVotingTypesEnum::FROM_DISLIKING_TO_LIKE->value,
                ]
            )) {
                return CommentVotedTypesEnum::VOTED;
            }
        }

        return CommentVotedTypesEnum::NOT_VOTED;
    }
}
