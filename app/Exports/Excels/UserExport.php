<?php

namespace App\Exports\Excels;

use App\Enums\Gates\RolesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Export\ExcelExport;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserExport extends ExcelExport
{
    protected array $dateColumnsNames = ['K', 'N'];

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
        $roles = RolesEnum::getTranslations($roles->pluck('name')->toArray(), '');

        return [
            $row->id,
            $row->username,
            $row->first_name,
            $row->last_name,
            $row->national_code,
            $row->sheba_number ?? '-',
            implode('-', array_values($roles)),
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
}
