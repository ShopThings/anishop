<?php

namespace App\Support\Helper;

if (!function_exists('get_color_from_bg')) {
    /**
     * @see https://betterprogramming.pub/generate-contrasting-text-for-your-random-background-color-ac302dc87b4
     *
     * @param string $bgColor
     * @param string $lightColor
     * @param string $darkColor
     * @return string
     */
    function get_color_from_bg(string $bgColor, string $lightColor = '#ffffff', string $darkColor = '#000000'): string
    {
        $color = ($bgColor[0] === '#') ? substr($bgColor, 1) : $bgColor;
        if (strlen($color) === 3) {
            $colorArr = array_map(function ($value) {
                return $value . $value;
            }, str_split($color));
            $color = implode('', $colorArr);
        }
        $r = hexdec(substr($color, 0, 2)); // hexToR
        $g = hexdec(substr($color, 2, 2)); // hexToG
        $b = hexdec(substr($color, 4, 2)); // hexToB
        $brightness = round((($r * 299) + ($g * 587) + ($b * 114)) / 1000);
        return $brightness > 150 ? $darkColor : $lightColor;
    }
}

if (!function_exists('get_db_comma_regex_string')) {
    /**
     * @param $needle
     * @return string
     */
    function get_db_comma_regex_string($needle): string
    {
        return '([^0-9]|^)' . preg_quote($needle) . '([^0-9]|$)';
    }
}

if (!function_exists('replaced_sms_body')) {
    /**
     * @param $type
     * @param array $placeholders
     * @return string
     */
    function replaced_sms_body($type, array $placeholders = []): string
    {
        if (!function_exists('message_replacer')) {
            /**
             * @param string $message
             * @param array $placeholders
             * @return string
             */
            function message_replacer(string $message, array $placeholders): string
            {
                if (!empty($message)) {
                    foreach ($placeholders as $placeholder => $value) {
                        if (is_scalar($value)) {
                            $message = str_replace($placeholder, $value, $message);
                        }
                    }
                }
                return $message;
            }
        }

        // ...
        $body = '';

        return message_replacer($body, $placeholders);
    }
}
