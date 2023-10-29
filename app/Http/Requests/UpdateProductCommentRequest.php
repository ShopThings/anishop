<?php

namespace App\Http\Requests;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProductCommentRequest extends FormRequest
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
            'condition' => [
                'sometimes',
                new Enum(CommentConditionsEnum::class),
            ],
            'status' => [
                'sometimes',
                new Enum(CommentStatusesEnum::class),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'condition' => 'وضعیت دیدگاه',
            'status' => 'وضعیت خوانده شدن',
        ];
    }
}
