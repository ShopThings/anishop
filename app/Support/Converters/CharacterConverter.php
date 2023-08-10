<?php

namespace App\Support\Converters;

final class CharacterConverter
{
    /**
     * @var array
     */
    private static array $persianSpecialCharacters = ['ا', 'ک', 'ه', 'ی'];

    /**
     * @var array
     */
    private static array $arabicSpecialCharacters = ['أ', 'ك', 'ة', 'ي'];

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
            $value = \str_replace(self::$arabicSpecialCharacters, self::$persianSpecialCharacters, $value);
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
            $value = \str_replace(self::$persianSpecialCharacters, self::$arabicSpecialCharacters, $value);
        }

        return $value;
    }
}
