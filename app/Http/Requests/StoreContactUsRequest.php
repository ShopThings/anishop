<?php

namespace App\Http\Requests;

use App\Rules\PersianMobileRule;
use App\Rules\PersianNameRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactUsRequest extends FormRequest
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
                'required',
                'max:250',
            ],
            'name' => [
                'required',
                'max:250',
                new PersianNameRule(),
            ],
            'mobile' => [
                'required',
                new PersianMobileRule(),
            ],
            'description' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'موضوع',
        ];
    }
}
