<?php

namespace App\Rules;

use App\Enums\DatabaseEnum;
use App\Models\City;
use App\Models\Province;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CityInProvinceRule implements DataAwareRule, ValidationRule
{
    /**
     * All data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $province = Province::find($this->data['province']);
        $city = null;

        if ($province) {
            $city = City::where('name', $value)
                ->where('province_id', $province->id)
                ->where('is_published', DatabaseEnum::DB_YES)
                ->first();
        }

        if (!$province || !$city) {
            $fail('شهر انتخاب شده داخل استان انتخاب شده نمی‌باشد!');
        }
    }

    /**
     * Set the data under validation.
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
