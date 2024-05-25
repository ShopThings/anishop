<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\PersianMobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecoverSendCodeRequest extends FormRequest
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
            'username' => [
                'required',
                Rule::exists(User::class, 'username')->where(function ($query) {
                    $query->whereNotNull('verified_at');
                }),
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
            'username.exists' => 'شماره موبایل وارد شده وجود ندارد/تایید نشده است.',
        ];
    }
}
