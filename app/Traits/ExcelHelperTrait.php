<?php

namespace App\Traits;

use App\Support\Converters\NumberConverter;
use PhpOffice\PhpSpreadsheet\Style\Fill;

trait ExcelHelperTrait
{
    /**
     * @param $sheet
     * @param string $cell
     * @param string $bgColor
     * @return void
     */
    protected function changeCellBackgroundColor($sheet, string $cell, string $bgColor): void
    {
        $sheet->getStyle($cell)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => $bgColor,
                ],
            ],
            'font' => [
                'color' => ['rgb' => get_color_from_bg($bgColor, 'FFFFFF', '000000')],
            ],
        ]);
    }

    /**
     * @param $number
     * @param $default
     * @param bool $toPersian
     * @return mixed|string|null
     */
    protected function formatNumber($number, $default = null, bool $toPersian = false): mixed
    {
        if (is_null($number)) return $default;

        $number = number_format($number);

        if ($toPersian) {
            $number = NumberConverter::toPersian($number);
        }

        return $number;
    }
}
