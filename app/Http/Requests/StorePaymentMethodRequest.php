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

class StorePaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::PAYMENT_METHOD
            ));
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
            'image_id' => [
                'required',
                'exists:' . FileManager::class . ',id',
            ],
            'type' => [
                'required',
                Rule::in(PaymentTypesEnum::cases()),
            ],
            'bank_gateway_type' => [
                'required',
                Rule::in(GatewaysEnum::cases()),
            ],
            'options' => [
                'array',
                'nullable',
            ],
            'is_published' => [
                'required',
                'boolean',
            ],
        ];
    }
}
