<?php

namespace App\Support\Export;

use App\Enums\Settings\SettingsEnum;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Filter;
use App\Traits\ExcelColumnsGeneratorTrait;
use App\Traits\ExcelHelperTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class ExcelExport implements
    ShouldQueue,
    FromCollection,
    ShouldAutoSize,
    WithProperties,
    WithStyles,
    WithColumnFormatting,
    WithMapping,
    WithTitle,
    WithHeadings
{
    use Exportable,
        ExcelColumnsGeneratorTrait,
        ExcelHelperTrait;

    protected string $writerType = Excel::XLSX;

    /**
     * @var array
     */
    protected array $dateColumnsNames = [];

    /**
     * @var array
     */
    protected array $numberColumnsNames = [];

    /**
     * @var int
     */
    protected int $numberOfHeadingColumns = 0;

    public function __construct(protected array $query, protected Filter $filter)
    {
        $this->numberOfHeadingColumns = count($this->headings()[0]);
    }

    /**
     * @inheritDoc
     */
    public function columnFormats(): array
    {
        $formattedColumns = [];
        foreach ($this->dateColumnsNames as $name) {
            $formattedColumns[$name] = 'yyyy-mm-dd h:mm';
        }
        foreach ($this->numberColumnsNames as $name) {
            $formattedColumns[$name] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1;
        }
        return $formattedColumns;
    }

    public function styles(Worksheet $sheet)
    {
        $len = $this->numberOfHeadingColumns;

        if ($len > 0) {
            $cells1 = $this->generateSemiExcelColumns($len);
            $row1 = 1;
            foreach ($cells1 as $cell) {
                $sheet->getCell($cell . $row1)->getStyle()->getFill()->applyFromArray([
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => Color::COLOR_YELLOW],
                ]);
            }

            $cells2 = $cells1;
            $row2 = 2;
            foreach ($cells2 as $cell) {
                $sheet->getCell($cell . $row2)->getStyle()->getBorders()->applyFromArray([
                    'top' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ],
                ]);
            }
        }

        //

        return [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
            ],
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function properties(): array
    {
        /**
         * @var SettingServiceInterface $settingService
         */
        $settingService = app()->get(SettingServiceInterface::class);
        $titleSetting = $settingService->getSetting(SettingsEnum::TITLE->value);

        if (is_null($titleSetting)) {
            return [];
        }

        $title = $titleSetting->setting_value ?: $titleSetting->default_value;

        return [
            'company' => $title,
        ];
    }
}
