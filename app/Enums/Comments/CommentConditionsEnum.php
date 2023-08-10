<?php

namespace App\Enums\Comments;

enum CommentConditionsEnum: string
{
    case UNSET = 'unset';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';
}
