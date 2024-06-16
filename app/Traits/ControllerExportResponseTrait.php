<?php

namespace App\Traits;

use App\Enums\Responses\ResponseTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Jobs\NotifyUserOfCompletedExportJob;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Support\Filter;
use App\Support\Traits\FilenameTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

trait ControllerExportResponseTrait
{
    use FilenameTrait;

    /**
     * @param Request $request
     * @param Filter $filter
     * @param string $exportName
     * @param string $exportClass
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    protected function exportResponse(
        Request $request,
        Filter  $filter,
        string  $exportName,
        string  $exportClass
    ): JsonResponse
    {
        $reportQuery = $request->input('query');

        if (!is_array($reportQuery)) {
            $reportQuery = null;
        }

        $filter
            ->setLimit(null)
            ->setOffset(0)
            ->setPage(1)
            ->setSearchText(null);

        $export = app()->make($exportClass, [
            'query' => $reportQuery,
            'filter' => $filter,
        ]);

        $filename = $exportName . '-report-' . vertaTz()->format(TimeFormatsEnum::EXPORT_FILENAME_WITH_TIME_AND_SECONDS->value);
        $filename = $this->getEscapedFilename($filename);

        $fullFilename = 'reports/' . $filename . '.xlsx';
        $storageDisk = FileRepositoryInterface::STORAGE_DISK_LOCAL;

        Excel::queue(
            $export,
            $this->getSaveFilename($fullFilename),
            $storageDisk
        )->chain([
            new NotifyUserOfCompletedExportJob(
                $request->user(),
                'reports/' . $filename,
                $fullFilename,
                $storageDisk
            ),
        ]);

        return response()->json([
            'type' => ResponseTypesEnum::INFO->value,
            'message' => 'گزارش‌گیری در حال انجام می‌باشد، پس از اتمام گزارش شما در پوشه ' .
                'reports' .
                ' قابل دسترس می‌باشد.',
        ], ResponseCodes::HTTP_ACCEPTED);
    }
}
