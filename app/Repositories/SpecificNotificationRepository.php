<?php

namespace App\Repositories;

use App\Models\NotificationMainNumber;
use App\Repositories\Contracts\SpecificNotificationRepositoryInterface;
use App\Support\Repository;

class SpecificNotificationRepository extends Repository implements SpecificNotificationRepositoryInterface
{
    public function __construct(NotificationMainNumber $model)
    {
        parent::__construct($model);
    }
}
