<?php

namespace App\Repositories;

use App\Models\GatewayPayment;
use App\Repositories\Contracts\GatewayPaymentRepositoryInterface;
use App\Support\Repository;

class GatewayPaymentRepository extends Repository implements GatewayPaymentRepositoryInterface
{
    protected bool $useSoftDeletes = false;

    public function __construct(GatewayPayment $model)
    {
        parent::__construct($model);
    }
}
