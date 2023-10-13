<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Models\FileManager;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdatePaymentMethodRequest extends FormRequest
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
            'image' => [
                'sometimes',
                'exists:' . FileManager::class . ',id',
            ],
            'type' => [
                'sometimes',
                new Enum(PaymentTypesEnum::class),
            ],
            'bank_gateway_type' => [
                'sometimes',
                new Enum(GatewaysEnum::class),
            ],
            'options' => [
                'sometimes',
                'array',
                'nullable',
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
            'type' => 'نوع روش پرداخت',
            'bank_gateway_type' => 'نوع درگاه',
            'options' => 'اطلاعات تکمیلی درگاه',
        ];
    }
}
