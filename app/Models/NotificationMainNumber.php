<?php

namespace App\Models;

use App\Enums\AccountTypesEnum;
use App\Enums\Notification\AccountNotificationTypesEnum;
use App\Support\Model\ExtendedModel as Model;

class NotificationMainNumber extends Model
{
    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'account_type' => AccountTypesEnum::class,
        'notification_type' => AccountNotificationTypesEnum::class,
    ];
}
