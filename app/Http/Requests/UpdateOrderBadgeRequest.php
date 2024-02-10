<?php

namespace App\Http\Requests;

use App\Rules\ColorRule;
use Illuminate\Foundation\Http\FormRequest;
use function App\Support\Helper\to_boolean;

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
        $model = $this->route('order_badge');

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
            'should_return_order_product' => [
                'sometimes',
                'boolean',
            ],
            'is_end_badge' => [
                'sometimes',
                'boolean',
                function ($attribute, $value, $fail) use ($model) {
                    if (to_boolean($value) && $model->is_starting_badge) {
                        $fail('برچسب شروع و پایان نمی‌تواند یکی باشد.');
                    }
                },
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
            'should_return_order_product' => 'وضعیت بازگشت محصولات به انبار',
            'is_end_badge' => 'وضعیت پایانی',
        ];
    }
}
