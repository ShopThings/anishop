<?php

namespace App\Http\Requests;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Models\BlogCommentBadge;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateBlogCommentRequest extends FormRequest
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
            'badge' => [
                'sometimes',
                'exists:' . BlogCommentBadge::class . ',id',
            ],
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
            'badge' => 'برچسب نظر',
            'condition' => 'وضعیت نظر',
            'status' => 'وضعیت خوانده شدن',
        ];
    }
}
