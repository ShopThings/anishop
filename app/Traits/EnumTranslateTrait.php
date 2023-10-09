<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait EnumTranslateTrait
{
    /**
     * @return string[]
     */
    abstract protected static function translationArray(): array;

    /**
     * @param array|string $statuses
     * @return array|string|null
     */
    public static function getTranslations(array|string $needed): array|string|null
    {
        $translates = self::translationArray();
        if (is_array($needed)) {
            $newArr = [];
            foreach ($needed as $item) {
                $newArr[$item] = $translates[$item] ?? $item;
            }
            return count($newArr) ? $newArr : null;
        }
        return $translates[$needed] ?? $needed;
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
