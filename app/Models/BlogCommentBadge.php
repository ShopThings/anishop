<?php

namespace App\Models;


use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;

class BlogCommentBadge extends Model
{
    use SoftDeletesTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasDeletedRelationTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_starting_badge' => 'boolean',
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
    ];
}
