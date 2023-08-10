<?php

namespace App\Support\Converters;

final class NumberConverter
{
    /**
     * @var array
     */
    private static array $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');

    /**
     * @var array
     */
    private static array $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');

    /**
     * @var array
     */
    private static array $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /**
     * @var array
     */
    private static array $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    /**
     * @var array
     */
    private static array $englishNumbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

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
            $value = \str_replace(self::$englishNumbers, self::$persianNumbers, $value);
            $value = \str_replace(self::$arabicNumbers, self::$persianNumbers, $value);
            $value = \str_replace(self::$arabicDecimal, self::$persianNumbers, $value);
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
            $value = \str_replace(self::$englishNumbers, self::$arabicNumbers, $value);
            $value = \str_replace(self::$persianNumbers, self::$arabicNumbers, $value);
            $value = \str_replace(self::$persianDecimal, self::$arabicNumbers, $value);
        }

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function toEnglish($value): mixed
    {
        if (\is_array($value)) {
            $newArr = [];
            foreach ($value as $k => $v) {
                $newArr[$k] = self::toEnglish($v);
            }
            return $newArr;
        }

        if (\is_string($value) || \is_numeric($value)) {
            $value = \str_replace(self::$arabicNumbers, self::$englishNumbers, $value);
            $value = \str_replace(self::$persianNumbers, self::$englishNumbers, $value);
            $value = \str_replace(self::$persianDecimal, self::$englishNumbers, $value);
            $value = \str_replace(self::$arabicDecimal, self::$englishNumbers, $value);
        }

        return $value;
    }
}
