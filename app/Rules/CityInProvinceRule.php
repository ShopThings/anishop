<?php

namespace App\Rules;

use App\Models\Province;
use App\Services\Contracts\CityServiceInterface;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
        $hasCity = false;

        if ($province) {

            /**
             * @var CityServiceInterface $service
             */
            try {
                $service = app()->get(CityServiceInterface::class);
                $hasCity = $service->isCityInProvince($this->data['city'], $province->id);
            } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            }
        }

        if (!$province || !$hasCity) {
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
