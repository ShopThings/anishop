<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\PaymentMethodResource as HomePaymentMethodResource;
use App\Services\Contracts\PaymentMethodServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomePaymentMethodController extends Controller
{
    /**
     * @param PaymentMethodServiceInterface $service
     */
    public function __construct(
        protected readonly PaymentMethodServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return HomePaymentMethodResource::collection($this->service->getHomeMethods());
    }
}
