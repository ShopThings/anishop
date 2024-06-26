<?php

namespace App\Http\Controllers\Report;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exports\Excels\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerExportResponseTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ReportProductController extends Controller
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
    public function products(Request $request, Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('canReport');

        $reportQuery = $request->input('query');

        if (!is_array($reportQuery)) {
            $reportQuery = null;
        }

        return ProductResource::collection($this->service->getProductsForReport($filter, $reportQuery));
    }

    /**
     * @return JsonResponse
     */
    public function productsQB(): JsonResponse
    {
        Gate::authorize('canReport');

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getProductsQueryBuilderInfo(),
        ]);
    }

    /**
     * @param Request $request
     * @param Filter $filter
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function export(Request $request, Filter $filter): JsonResponse
    {
        Gate::authorize('canReport');

        return $this->exportResponse($request, $filter, 'products', ProductExport::class);
    }
}
