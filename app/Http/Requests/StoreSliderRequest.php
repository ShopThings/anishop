<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Sliders\SliderPlacesEnum;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StoreSliderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::SLIDER
            ));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'slider_place' => [
                'required',
                new Enum(SliderPlacesEnum::class),
                function ($attribute, $value, $fail) {
                    $creatable = array_map(fn($item) => $item->value, SliderPlacesEnum::getCreatablePlaces());
                    if (!in_array($value, $creatable)) {
                        $fail('اسلایدر با محل نمایش انتخاب شده، قابل ساخت نمی‌باشد.');
                    }
                },
            ],
            'title' => [
                'required',
                'max:250',
            ],
            'priority' => [
                'required',
                'numeric',
                'min:0',
            ],
            'options' => [
                'array',
            ],
            'is_published' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'slider_place' => 'محل نمایش',
            'options' => 'اطلاعات اضافی',
        ];
    }
}
