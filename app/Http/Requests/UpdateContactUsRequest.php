<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactUsRequest extends FormRequest
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
            'answer' => [
                'sometimes',
            ],
            'is_seen' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'answer' => 'پاسخ به پیام کاربر',
            'is_seen' => 'وضعیت بازدید',
        ];
    }
}
