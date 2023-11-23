<?php

namespace App\Support\Model;

use App\Models\BlogCommentBadge;
use App\Models\OrderBadge;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CodeGeneratorHelper
{
    /**
     * @param Builder $model
     * @param string $column
     * @param int $length
     * @return string
     */
    public static function generateCode(Builder $model, string $column, int $length): string
    {
        do {
            $code = Str::random($length);
        } while ($model->where($column, $code)->exists());
        return $code;
    }
}
