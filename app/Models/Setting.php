<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasUpdatedRelationTrait;

class Setting extends Model
{
    use HasUpdatedRelationTrait;

    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];
}
