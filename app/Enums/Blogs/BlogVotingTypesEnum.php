<?php

namespace App\Enums\Blogs;

enum BlogVotingTypesEnum: int
{
    case VOTED = 1;
    case NOT_VOTED = 2;
    case NOT_SET = 3;
}
