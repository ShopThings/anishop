<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class StringToArray implements CastsAttributes
{
    public function __construct(protected $separator = ',')
    {
    }

    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $v = explode($this->separator, $value);
        return array_filter($v, fn($item) => trim($item) !== '');
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return implode($this->separator, $value);
    }
}
