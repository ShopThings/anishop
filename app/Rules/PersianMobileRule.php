<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PersianMobileRule implements ValidationRule
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match("/^(098|\+98|0)?9\d{9}$/", $value)) {
            $fail('validation.persian_mobile')->translate();
        }
    }
}
