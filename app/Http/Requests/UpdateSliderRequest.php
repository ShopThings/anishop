<?php

namespace App\Http\Requests;

use App\Enums\Sliders\SliderPlacesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateSliderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
                'sometimes',
                new Enum(SliderPlacesEnum::class),
            ],
            'title' => [
                'sometimes',
                'max:250',
            ],
            'priority' => [
                'sometimes',
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
