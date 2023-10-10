<?php

namespace App\Models;

use App\Casts\StringToArray;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Mews\Purifier\Casts\CleanHtml;

class StaticPage extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'keywords' => StringToArray::class,
        'description' => CleanHtml::class,
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
    ];
}
