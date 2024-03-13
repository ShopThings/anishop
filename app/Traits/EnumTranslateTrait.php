<?php

namespace App\Traits;

use BackedEnum;
use Illuminate\Support\Str;

trait EnumTranslateTrait
{
    /**
     * @return string[]
     */
    abstract public static function translationArray(): array;

    /**
     * @param BackedEnum|array|string $needed
     * @param $default
     * @return array|string|null
     */
    public static function getTranslations(BackedEnum|array|string $needed, $default = null): array|string|null
    {
        $translates = self::translationArray();

        $isOriginalParameterArray = is_array($needed);

        $needed = is_array($needed) ? $needed : [$needed];
        $newArr = [];
        foreach ($needed as $item) {
            if ($item instanceof BackedEnum) {
                $newArr[$item->value] = $translates[$item->value] ?? $item->value;
            } else {
                $newArr[$item] = $translates[$item] ?? $item;
            }
        }

        return count($newArr)
            ? (
            !$isOriginalParameterArray
                ? array_pop($newArr)
                : $newArr
            )
            : ($default ?? null);
    }

    /**
     * @param string $str
     * @return array
     */
    public static function getSimilarValuesFromString(string $str): array
    {
        $translates = self::translationArray();
        $arr = [];
        foreach ($translates as $similar => $translate) {
            if (Str::contains($translate, $str, true)) {
                $arr[] = $similar;
            }
        }
        return $arr;
    }
}
