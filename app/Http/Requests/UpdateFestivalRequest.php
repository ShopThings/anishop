<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFestivalRequest extends FormRequest
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
            'title' => [
                'sometimes',
                'max:250',
            ],
            'start_at' => [
                'sometimes',
                'date-format:Y-m-d H:i',
                'before:end_at',
            ],
            'end_at' => [
                'sometimes',
                'date-format:Y-m-d H:i',
                'after:start_at',
            ],
            'is_published' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'start_at' => 'تاریخ شروع',
            'end_at' => 'تاریخ پایان',
        ];
    }
}
