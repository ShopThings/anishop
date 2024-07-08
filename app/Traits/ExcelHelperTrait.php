<?php

namespace App\Traits;

use App\Support\Converters\NumberConverter;
use Illuminate\Support\Arr;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
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
     * @param $sheet
     * @param string $cell
     * @param array $data
     * @return void
     */
    public function changeCellAlignment($sheet, string $cell, array $data): void
    {
        $sheet->getStyle($cell)->getAlignment()->applyFromArray($data);
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

    /**
     * @param $sheet
     * @param string $from
     * @param string $to
     * @param $totalRow
     * @return void
     */
    protected function addStyleToTotalColumns($sheet, string $from, string $to, $totalRow): void
    {
        $this->changeCellAlignment($sheet, $from . $totalRow . ':' . $to . $totalRow, [
            'readOrder' => Alignment::READORDER_RTL,
        ]);

        $sheet->getStyle($from . $totalRow . ':' . $to . $totalRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'DDDDDD'],
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }

    /**
     * @param $sheet
     * @param int $row
     * @param array $content
     * @return void
     */
    protected function addContentToSpecificRow($sheet, int $row, array $content): void
    {
        if (!count($content)) return;

        foreach ($content as $column => $value) {
            $sheet->setCellValue($column . $row, $value);
        }

        $columns = array_keys($content);
        sort($columns);
        $this->addStyleToTotalColumns($sheet, Arr::first($columns), Arr::last($columns), $row);
    }
}
