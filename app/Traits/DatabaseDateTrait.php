<?php

namespace App\Traits;

use App\Enums\Times\TimeFormatsEnum;
use Carbon\Carbon;

trait DatabaseDateTrait
{
    use AppTimezoneTrait,
        CompanyTimezoneDetectorTrait;

    /**
     * @param $date
     * @return Carbon
     */
    protected function getValidDatabaseDate($date): Carbon
    {
        return Carbon::createFromFormat(
            TimeFormatsEnum::NORMAL_DATETIME->value,
            $date,
            $this->getCompanyTimezone()
        )->timezone($this->getAppTimezone());
    }
}
