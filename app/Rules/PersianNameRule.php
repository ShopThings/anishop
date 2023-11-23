<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PersianNameRule implements ValidationRule
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!preg_match('/^[‌پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u', $value)) {
            $fail('validation.persian_name')->translate();
        }
    }
}
