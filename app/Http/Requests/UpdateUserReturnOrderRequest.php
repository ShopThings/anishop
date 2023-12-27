<?php

namespace App\Http\Requests;

use App\Models\ReturnOrderRequest;
use App\Models\ReturnOrderRequestItem;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserReturnOrderRequest extends FormRequest
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
            'description' => [
                'sometimes',
                'max:250',
            ],
            'items' => [
                'sometimes',
                'array',
                'min:1',
            ],
            'items.*.id' => [
                'required_with:items',
                'exists:' . ReturnOrderRequestItem::class . ',id',
            ],
            'items.*.quantity' => [
                'required_with:items',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'description' => 'علت مرجوع',
            'items' => 'آیتم‌های مرجوع',
            'items.*.id' => 'شناسه آیتم مرجوعی',
            'items.*.quantity' => 'تعداد آیتم مرجوعی',
        ];
    }
}
