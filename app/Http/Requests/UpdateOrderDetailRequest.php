<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\OrderBadge;
use App\Models\Province;
use App\Rules\PersianMobileRule;
use App\Rules\PersianNameRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderDetailRequest extends FormRequest
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
            'province' => [
                'sometimes',
                'exists:' . Province::class . ',id',
            ],
            'city' => [
                'sometimes',
                'exists:' . City::class . ',id',
            ],
            'address' => [
                'sometimes',
                'max:250',
            ],
            'postal_code' => [
                'sometimes',
                'max:15',
            ],
            'receiver_name' => [
                'sometimes',
                new PersianNameRule(),
                'max:250',
            ],
            'receiver_mobile' => [
                'sometimes',
                new PersianMobileRule(),
            ],
            'send_status' => [
                'sometimes',
                'exists:' . OrderBadge::class . ',id',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'receiver_name' => 'نام گیرنده',
            'receiver_mobile' => 'شماره گیرنده',
            'send_status' => 'وضعیت ارسال',
        ];
    }
}
