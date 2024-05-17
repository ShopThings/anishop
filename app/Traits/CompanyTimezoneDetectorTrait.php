<?php

namespace App\Traits;

trait CompanyTimezoneDetectorTrait
{
    /**
     * @return string
     */
    protected function getCompanyTimezone(): string
    {
        // For now, it's just one place for company, and it is in IRAN.
        // Extend this if there is more than one timezone...
        return 'Asia/Tehran';
    }
}
