<?php

namespace App\Support\Traits;

trait ServiceTrait
{
    /**
     * @var string $version
     */
    protected static string $version = 'v0.1.0';

    /**
     * {@inheritdoc}
     */
    public static function version(): string
    {
        return self::$version;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion(): string
    {
        return self::version();
    }
}
