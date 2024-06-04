<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Parables\NanoId\GeneratesNanoId;

class OrderBadge extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        GeneratesNanoId;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_starting_badge' => 'boolean',
        'should_return_order_product' => 'boolean',
        'is_title_editable' => 'boolean',
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
    ];

    public static function nanoIdColumn(): string
    {
        return 'code';
    }
}
