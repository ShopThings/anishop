<?php

namespace App\Enums\Comments;

use App\Traits\EnumTranslateTrait;

enum CommentConditionsEnum: string
{
    use EnumTranslateTrait;

    case UNSET = 'unset';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::UNSET->value => 'در حال بررسی',
            self::REJECTED->value => 'عدم تایید',
            self::ACCEPTED->value => 'تایید شده',
        ];
    }
}
