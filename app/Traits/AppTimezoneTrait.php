<?php

namespace App\Traits;

trait AppTimezoneTrait
{
    /**
     * @return string
     */
    protected function getAppTimezone(): string
    {
        return config('app.timezone', 'UTC');
    }
}
