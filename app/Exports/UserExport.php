<?php

namespace App\Exports;

use App\Enums\Gates\RolesEnum;
use App\Enums\Settings\SettingsEnum;
use App\Services\Contracts\ReportServiceInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserExport implements
    ShouldQueue,
    FromCollection,
    ShouldAutoSize,
    WithProperties,
    WithStyles,
    WithMapping,
    WithTitle,
    WithHeadings
{
    use Exportable;

    private string $writerType = Excel::XLSX;

    public function __construct(protected array $query, protected Filter $filter)
    {
    }

    /**
     * @return Collection
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function collection()
    {
        /**
         * @var ReportServiceInterface $service
         */
        $service = app()->get(ReportServiceInterface::class);
        return $service->getUsersForReport($this->filter, $this->query);
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return 'users';
    }

    /**
     * @inheritDoc
     */
    public function map($row): array
    {
        $roles = $row->roles;
        $roles = RolesEnum::getTranslations($roles, '');

        return [
            $row->id,
            $row->username,
            $row->first_name,
            $row->last_name,
            $row->national_code,
            $row->sheba_number,
            implode('-', $roles),
            $row->is_admin,
            $row->is_banned,
            $row->ban_desc,
            $row->verified_at ? Date::dateTimeToExcel($row->verified_at) : null,
            $row->verified_at ? vertaTz($row->verified_at) : null,
            $row->is_deletable,
            $row->created_at ? Date::dateTimeToExcel($row->created_at) : null,
            $row->created_at ? vertaTz($row->created_at) : null,
        ];
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            [
                '#',
                'نام کاربری',
                'نام',
                'نام خانوادگی',
                'کد ملی',
                'شماره شبا',
                'نقش‌ها',
                'کاربر ادمین',
                'کاربر بن شده',
                'علت بن شدن',
                'تاریخ تایید',
                'تاریخ تایید (تهران)',
                'اجازه حذف شدن دارد',
                'تاریخ ایجاد',
                'تاریخ ایجاد (تهران)',
            ],
            [
                '#',
                'Username',
                'First Name',
                'Last Name',
                'National Code',
                'Sheba Number',
                'Roles',
                'Is Admin',
                'Is Banned',
                'Ban Description',
                'Verified At',
                'Verified At (Asia/Tehran)',
                'Is Deletable',
                'Created At',
                'Created At (Asia/Tehran)',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
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
