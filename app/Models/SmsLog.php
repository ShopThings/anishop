<?php

namespace App\Models;

use App\Enums\SMS\SMSSenderTypesEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasCreatedRelationTrait;

class SmsLog extends Model
{
    use HasCreatedRelationTrait;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'type' => SMSTypesEnum::class,
        'sender' => SMSSenderTypesEnum::class,
    ];
}
