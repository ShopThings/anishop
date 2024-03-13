<?php

namespace App\Http\Requests;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Models\ReturnOrderRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateReturnOrderRequest extends FormRequest
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
            'not_accepted_description' => [
                'sometimes',
                'min:1',
            ],
            'status' => [
                'sometimes',
                new Enum(ReturnOrderStatusesEnum::class),
            ],
            'seen_status' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'not_accepted_description' => 'علت عدم تایید',
            'status' => 'وضعیت بررسی',
            'seen_status' => 'وضعیت بازدید',
        ];
    }
}
