<?php

namespace App\Http\Controllers\Report;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exports\Excels\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerExportResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ReportOrderController extends Controller
{
    use ControllerExportResponseTrait;

    /**
     * @param ReportServiceInterface $service
     */
    public function __construct(
        protected readonly ReportServiceInterface $service
    )
    {
    }

    /**
     * @param Request $request
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function orders(Request $request, Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('canReport');

        $reportQuery = $request->input('query');

        if (!is_array($reportQuery)) {
            $reportQuery = null;
        }

        return OrderResource::collection($this->service->getOrdersForReport($filter, $reportQuery));
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

    /**
     * @param Request $request
     * @param Filter $filter
     * @return JsonResponse
     */
    public function export(Request $request, Filter $filter): JsonResponse
    {
        Gate::authorize('canReport');

        return $this->exportResponse($request, $filter, 'products', OrderExport::class);
    }
}
