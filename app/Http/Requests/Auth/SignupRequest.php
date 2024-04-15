<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\PersianMobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignupRequest extends FormRequest
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
            'captcha' => ['required', 'captcha_api:' . $this->input('key')],
            'username' => [
                'required',
                Rule::exists(User::class, 'username')->where(function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery
                            ->whereNull('verified_at') // User is not verified
                            ->orWhereNull('id'); // User doesn't exist
                    });
                }),
                'unique:' . User::class . ',username',
                new PersianMobileRule,
            ],
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'شماره موبایل',
        ];
    }

    public function messages()
    {
        return [
            'username.exists' => 'شماره موبایل وارد شده وجود دارد/قبلا تایید شده است.',
        ];
    }
}
