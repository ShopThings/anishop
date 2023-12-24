<?php

namespace App\Enums\Comments;

enum CommentVotingTypesEnum: int
{
    case LIKING = 1;
    case UNDO_LIKING = 2;
    case DISLIKING = 3;
    case UNDO_DISLIKING = 4;
    case FROM_LIKE_TO_DISLIKING = 5;
    case FROM_DISLIKING_TO_LIKE = 6;
}
