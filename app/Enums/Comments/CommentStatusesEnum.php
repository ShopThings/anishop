<?php

namespace App\Enums\Comments;

enum CommentStatusesEnum: string
{
    case UNREAD = 'unread';
    case READ = 'read';
}
