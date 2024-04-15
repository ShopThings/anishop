<?php

namespace App\Http\Controllers\Report;

use App\Enums\Responses\ResponseTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Jobs\NotifyUserOfCompletedExportJob;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ReportUserController extends Controller
{
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
     */
    public function export(Request $request, Filter $filter): JsonResponse
    {
        Gate::authorize('canReport');

        $reportQuery = $request->input('query');

        if (!is_array($reportQuery)) {
            $reportQuery = null;
        }

        $filter
            ->setLimit(null)
            ->setOffset(0)
            ->setPage(1)
            ->setSearchText(null);

        $filename = 'users-report-' . vertaTz()->format(TimeFormatsEnum::NORMAL_DATETIME->value);

        Excel::queue(
            new UserExport($reportQuery, $filter),
            'reports/' . $filename . '.xlsx',
            'local'
        )->chain([
            new NotifyUserOfCompletedExportJob($request->user(), 'reports/' . $filename),
        ]);

        return response()->json([
            'type' => ResponseTypesEnum::INFO->value,
            'message' => 'گزارش‌گیری در حال انجام می‌باشد، پس از اتمام گزارش شما در پوشه ' .
                'reports' .
                ' قابل دسترس می‌باشد.',
        ], ResponseCodes::HTTP_ACCEPTED);
    }
}
