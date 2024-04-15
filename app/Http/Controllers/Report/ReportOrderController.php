<?php

namespace App\Http\Controllers\Report;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Services\Contracts\ReportServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ReportOrderController extends Controller
{
    /**
     * @param ReportServiceInterface $service
     */
    public function __construct(
        protected readonly ReportServiceInterface $service
    )
    {
    }

    public function orders()
    {
        Gate::authorize('canReport');


    }

    /**
     * @return JsonResponse
     */
    public function ordersQB(): JsonResponse
    {
        Gate::authorize('canReport');

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getOrdersQueryBuilderInfo(),
        ]);
    }

    public function export()
    {

    }
}
