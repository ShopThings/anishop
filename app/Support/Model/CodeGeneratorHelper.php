<?php

namespace App\Support\Model;

use App\Models\BlogCommentBadge;
use App\Models\OrderBadge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CodeGeneratorHelper
{
    /**
     * @param int $length
     * @return string
     */
    public static function orderBadgeCode(int $length = 12): string
    {
        return static::generateCode(OrderBadge::query(), 'code', $length);
    }

    /**
     * @param Builder $model
     * @param string $column
     * @param int $length
     * @return string
     */
    private static function generateCode(Builder $model, string $column, int $length): string
    {
        do {
            $code = Str::random($length);
        } while($model->where($column, $code)->exists());
        return $code;
    }
}
