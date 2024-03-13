<?php

namespace App\Http\Requests;

use App\Enums\Comments\CommentVotingTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProductCommentVoteRequest extends FormRequest
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
            'vote' => [
                'required',
                new Enum(CommentVotingTypesEnum::class),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'vote' => 'رأی برای نظر محصول',
        ];
    }
}
