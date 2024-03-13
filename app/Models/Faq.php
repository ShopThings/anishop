<?php

namespace App\Models;

use App\Casts\CleanHtmlCast;
use App\Casts\StringToArray;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;

class Faq extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'answer' => CleanHtmlCast::class,
        'keywords' => StringToArray::class,
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
    ];
}
