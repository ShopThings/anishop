<?php

namespace App\Http\Controllers\Report;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exports\Excels\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerExportResponseTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ReportUserController extends Controller
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
    public function users(Request $request, Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('canReport');

        $reportQuery = $request->input('query');

        if (!is_array($reportQuery)) {
            $reportQuery = null;
        }

        return UserResource::collection($this->service->getUsersForReport($filter, $reportQuery));
    }

    /**
     * @return JsonResponse
     */
    public function usersQB(): JsonResponse
    {
        Gate::authorize('canReport');

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getUsersQueryBuilderInfo(),
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

        return $this->exportResponse($request, $filter, 'users', UserExport::class);
    }
}
