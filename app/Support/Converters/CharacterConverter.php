<?php

namespace App\Support\Converters;

final class CharacterConverter
{
    /**
     * @var array
     */
    private static array $toPersianSpecialCharacters = ['ا', 'ک', 'ه', 'ی'];

    /**
     * @var array
     */
    private static array $fromArabicSpecialCharacters = ['أ', 'ك', 'ة', 'ي'];

    /**
     * @var array
     */
    private static array $fromPersianSpecialCharacters = ['ا', 'گ', 'چ', 'پ', 'ژ', 'ه', 'ی'];

    /**
     * @var array
     */
    private static array $toArabicSpecialCharacters = ['أ', 'ك', 'ج', 'ب', 'ز', 'ة', 'ي'];

    /**
     * @param $value
     * @return mixed
     */
    public static function toPersian($value): mixed
    {
        if (\is_array($value)) {
            $newArr = [];
            foreach ($value as $k => $v) {
                $newArr[$k] = self::toPersian($v);
            }
            return $newArr;
        }

        if (\is_string($value) || \is_numeric($value)) {
            $value = \str_replace(self::$fromArabicSpecialCharacters, self::$toPersianSpecialCharacters, $value);
        }

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function toArabic($value): mixed
    {
        if (\is_array($value)) {
            $newArr = [];
            foreach ($value as $k => $v) {
                $newArr[$k] = self::toArabic($v);
            }
            return $newArr;
        }

        if (\is_string($value) || \is_numeric($value)) {
            $value = \str_replace(self::$fromPersianSpecialCharacters, self::$toArabicSpecialCharacters, $value);
        }

        return $value;
    }
}
