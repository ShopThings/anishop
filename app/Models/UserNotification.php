<?php

namespace App\Models;

use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasCreatedRelationTrait;
use Mews\Purifier\Casts\CleanHtml;

class UserNotification extends Model
{
    use HasCreatedRelationTrait;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'description' => CleanHtml::class,
        'type' => UserNotificationTypesEnum::class,
        'priority' => UserNotificationPrioritiesEnum::class,
        'seen_status' => 'boolean',
    ];
}
