<?php

namespace App\Traits;

trait VersionTrait
{
    /**
     * @var string $version
     */
    protected static string $version = 'v0.1.0';

    /**
     * @inheritDoc
     */
    public static function version(): string
    {
        return self::$version;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return self::version();
    }
}
