<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentMethodRepositoryInterface;
use App\Services\Contracts\PaymentMethodServiceInterface;
use App\Support\Service;

class PaymentMethodService extends Service implements PaymentMethodServiceInterface
{
    public function __construct(protected PaymentMethodRepositoryInterface $repository)
    {

    }
}
