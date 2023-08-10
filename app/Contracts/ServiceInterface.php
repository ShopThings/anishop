<?php

namespace App\Contracts;

interface ServiceInterface
{
    /**
     * A version like semantic versioning
     *
     * @return string
     */
    public static function version(): string;

    /**
     * A version like semantic versioning
     *
     * @return string
     */
    public function getVersion(): string;
}
