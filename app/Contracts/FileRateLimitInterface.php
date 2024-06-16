<?php

namespace App\Contracts;

interface FileRateLimitInterface
{
    /**
     * @return string
     */
    public function getJobGroup(): string;
}
