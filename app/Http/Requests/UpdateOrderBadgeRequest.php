<?php

namespace App\Http\Requests;

use App\Rules\ColorRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderBadgeRequest extends FormRequest
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
            'color_hex' => [
                'sometimes',
                'max:12',
                new ColorRule(),
            ],
            'is_starting_badge' => [
                'sometimes',
                'boolean',
            ],
            'should_return_order_product' => [
                'sometimes',
                'boolean',
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
            'color_hex' => 'کد رنگ',
            'is_starting_badge' => 'برچسب شروع',
            'should_return_order_product' => 'وضعیت بازگشت محصولات به انبار',
        ];
    }
}
