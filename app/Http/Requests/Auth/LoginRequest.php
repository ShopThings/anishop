<?php

namespace App\Http\Requests\Auth;

use App\Rules\PersianMobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'captcha' => ['required', 'captcha_api:' . $this->input('key')],
            'username' => ['required', new PersianMobileRule],
            'password' => 'required',
            'remember' => 'boolean',
        ];
    }
}
