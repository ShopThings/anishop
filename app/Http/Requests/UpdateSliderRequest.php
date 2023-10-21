<?php

namespace App\Http\Requests;

use App\Models\SliderPlace;
use Illuminate\Foundation\Http\FormRequest;

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
                'exists:' . SliderPlace::class . ',id',
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
                'sometimes',
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
