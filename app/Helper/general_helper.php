<?php

use App\Enums\SMS\SMSTypesEnum;
use Hekmatinasser\Verta\Verta;
use Snortlin\NanoId\NanoId;
use Snortlin\NanoId\NanoIdInterface;

if (!function_exists('to_boolean')) {
    /**
     * Convert to boolean
     *
     * @param $booleanable
     * @return boolean
     */
    function to_boolean($booleanable): bool
    {
        return (bool)filter_var($booleanable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

if (!function_exists('get_random_verification_code')) {
    /**
     * @param int $length
     * @return string
     */
    function get_random_verification_code($length = 6): string
    {
        $pool = '0123456789';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}

if (!function_exists('get_nanoid')) {
    /**
     * @return string
     */
    function get_nanoid($alphabet = NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE): string
    {
        return NanoId::nanoId(
            NanoIdInterface::SIZE_DEFAULT,
            $alphabet
        );
    }
}

if (!function_exists('get_db_ancestry_regex_string')) {
    /**
     * @param $needle
     * @return string
     */
    function get_db_ancestry_regex_string($needle): string
    {
        return '([^0-9]|^)' . preg_quote($needle) . '([^0-9]|$)';
    }
}

if (!function_exists('vertaTz')) {
    /**
     * @param $datetime
     * @param string $timezone
     * @return Verta
     */
    function vertaTz($datetime = null, string $timezone = 'Asia/Tehran'): Verta
    {
        return verta($datetime)->timezone($timezone);
    }
}

if (!function_exists('replace_sms_variables')) {
    /**
     * @param string $body
     * @param SMSTypesEnum $type
     * @param array $replacements
     * @return string
     */
    function replace_sms_variables(string $body, SMSTypesEnum $type, array $replacements = []): string
    {
        $allReplacements = config('market:sms.replacements', []);
        if (!count($allReplacements)) return $body;

        $availableReplacements = SMSTypesEnum::replacementsArray($type);

        foreach ($replacements as $key => $value) {
            if (is_scalar($value) && in_array($key, $availableReplacements)) {
                $body = str_replace($allReplacements[$key], $value, $body);
            }
        }

        return $body;
    }
}

/**
 * This is a copy of helper function from below URL. [THANKS TO CREATOR 👏]
 * @see https://github.com/pishran/persian-slug/blob/master/src/persian-slug.php
 */
if (!function_exists('str_slug_persian')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param string $title
     * @param string $separator
     * @return string
     */
    function str_slug_persian(string $title, string $separator = '-'): string
    {
        $title = trim($title);
        $title = mb_strtolower($title, 'UTF-8');

        $title = str_replace('‌', $separator, $title);

        $title = preg_replace(
            '/[^a-z0-9_\s\-اآؤئبپتثجچحخدذرزژسشصضطظعغفقكکگلمنوةيإأۀءهی۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩]/u',
            '',
            $title
        );

        $title = preg_replace('/[\s\-_]+/', ' ', $title);
        $title = preg_replace('/[\s_]/', $separator, $title);
        $title = trim($title, $separator);

        return $title;
    }
}
