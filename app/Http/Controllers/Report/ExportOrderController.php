<?php

namespace App\Http\Controllers\Report;

use App\Enums\Settings\SettingsEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Services\Contracts\SettingServiceInterface;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ExportOrderController extends Controller
{
    /**
     * @param OrderDetail $order
     * @param SettingServiceInterface $settingService
     * @return SymfonyResponse
     * @throws MpdfException
     */
    public function pdf(
        OrderDetail             $order,
        SettingServiceInterface $settingService
    ): SymfonyResponse
    {
        $titleSetting = $settingService->getSetting(SettingsEnum::TITLE->value);
        $title = $titleSetting->setting_value ?: $titleSetting->default_value;

        $filename = 'order-' . $order->code . '-' .
            verta()->format(TimeFormatsEnum::EXPORT_FILENAME_WITH_TIME_AND_SECONDS->value);

        $pdf = LaravelMpdf::loadView(view: 'exports.invoice', data: ['detail' => $order], config: [
            'format' => [292.1, 215.9],
            'title' => 'فاکتور سفارش به کد: ' . $order->code,
            'watermark' => $title,
        ]);
        $pdf->getMpdf()->SetDirectionality('rtl');

        return $pdf->download($filename);
    }
}
