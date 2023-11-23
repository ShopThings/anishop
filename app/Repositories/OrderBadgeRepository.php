<?php

namespace App\Repositories;

use App\Models\OrderBadge;
use App\Repositories\Contracts\OrderBadgeRepositoryInterface;
use App\Support\Repository;

class OrderBadgeRepository extends Repository implements OrderBadgeRepositoryInterface
{
    public function __construct(OrderBadge $model)
    {
        parent::__construct($model);
    }
}
