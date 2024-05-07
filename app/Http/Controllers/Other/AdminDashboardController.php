<?php

namespace App\Http\Controllers\Other;

use App\Enums\Charts\ChartPeriodsEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnOrderRequest;
use App\Models\User;
use App\Services\Contracts\PeriodicServiceInterface;
use Illuminate\Support\Facades\Gate;

class AdminDashboardController extends Controller
{
    /**
     * @param PeriodicServiceInterface $service
     */
    public function __construct(
        protected PeriodicServiceInterface $service
    )
    {
    }

    public function totalSell()
    {
        Gate::authorize('viewAny', Order::class);

    }

    public function periodSell(string $period)
    {
        Gate::authorize('viewAny', Order::class);

        $period = $this->getValidatedPeriod($period);
    }

    /**
     * @param string $period
     * @return ChartPeriodsEnum
     */
    private function getValidatedPeriod(string $period): ChartPeriodsEnum
    {
        $period = ChartPeriodsEnum::tryFrom($period);

        if (is_null($period)) {
            return ChartPeriodsEnum::TODAY;
        }

        return $period;
    }

    public function chartUsers(string $period)
    {
        Gate::authorize('viewAny', User::class);

        $period = $this->getValidatedPeriod($period);
    }

    public function chartOrders(string $period)
    {
        Gate::authorize('viewAny', Order::class);

        $period = $this->getValidatedPeriod($period);
    }

    public function chartReturnOrders(string $period)
    {
        Gate::authorize('viewAny', ReturnOrderRequest::class);

        $period = $this->getValidatedPeriod($period);
    }

    public function tableMostSellProducts(string $period)
    {
        Gate::authorize('viewAny', Product::class);

        $period = $this->getValidatedPeriod($period);
    }
}
