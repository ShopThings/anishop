<?php

namespace App\Exports\Excels;

use App\Enums\Gates\RolesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Export\ExcelExport;
use App\Support\Filter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UserExport extends ExcelExport implements WithEvents
{
    protected array $dateColumnsNames = ['K', 'N'];

    /**
     * @var Collection
     */
    protected Collection $users;

    public function __construct(array $query, Filter $filter)
    {
        parent::__construct($query, $filter);

        /**
         * @var ReportServiceInterface $service
         */
        $service = app()->get(ReportServiceInterface::class);
        $this->users = $service->getUsersForReport($this->filter, $this->query);
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->users;
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
        $roles = RolesEnum::getTranslations($roles->pluck('name')->toArray(), '');

        return [
            $row->id,
            $row->username,
            $row->first_name,
            $row->last_name,
            $row->national_code,
            $row->sheba_number ?? '-',
            implode('-', array_values($roles ?? ['فاقد نقش'])),
            (bool)$row->is_admin,
            (bool)$row->is_banned,
            $row->ban_desc ?? '-',
            $row->verified_at
                ? Date::dateTimeToExcel($row->verified_at)
                : null,
            $row->verified_at
                ? vertaTz($row->verified_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            (bool)$row->is_deletable,
            $row->created_at
                ? Date::dateTimeToExcel($row->created_at)
                : null,
            $row->created_at
                ? vertaTz($row->created_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
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

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                // Calculate totals
                $totalCount = 0;
                $totalAdminCount = 0;
                $totalUserCount = 0;
                $totalNotVerifiedCount = 0;
                foreach ($this->users as $user) {
                    if ($user->is_admin) {
                        $totalAdminCount += 1;
                    } else {
                        $totalUserCount += 1;
                    }

                    if (empty($user->verified_at)) {
                        $totalNotVerifiedCount += 1;
                    }

                    $totalCount += 1;
                }

                // Add total row
                $totalRow = $lastRow + 1;

                $this->addContentToSpecificRow($sheet, $totalRow, [
                    'B' => 'تعداد کل کاربران',
                    'C' => $totalCount,
                ]);

                $this->addContentToSpecificRow($sheet, $totalRow, [
                    'D' => 'تعداد کاربران ادمین',
                    'E' => $totalAdminCount,
                ]);

                $this->addContentToSpecificRow($sheet, $totalRow, [
                    'F' => 'تعداد کاربران معمولی',
                    'G' => $totalUserCount,
                ]);

                $this->addContentToSpecificRow($sheet, $totalRow, [
                    'H' => 'تعداد کاربران تایید نشده',
                    'I' => $totalNotVerifiedCount,
                ]);
            },
        ];
    }
}
