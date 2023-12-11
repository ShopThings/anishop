<?php

namespace App\Support\Helper;

use App\Enums\SMS\SMSTypesEnum;
use Snortlin\NanoId\NanoId;
use Snortlin\NanoId\NanoIdInterface;

if (!function_exists('to_boolean')) {
    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    function to_boolean($booleable): bool
    {
        return (bool)filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
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
    function get_nanoid(): string
    {
        return NanoId::nanoId(
            NanoIdInterface::SIZE_DEFAULT,
            NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE
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
