<?php

namespace Modules\Settings\Http\Requests\Review;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $reviewId = $this->route('id');

        return [
            'user_id' => ['required', 'exists:users,id'],
            'trip_id' => ['required', 'exists:trips,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'معرف المستخدم مطلوب.',
            'user_id.exists' => 'المستخدم المحدد غير موجود.',

            'trip_id.required' => 'معرف الرحلة مطلوب.',
            'trip_id.exists' => 'الرحلة المحددة غير موجودة.',

            'rating.required' => 'التقييم مطلوب.',
            'rating.integer' => 'التقييم يجب أن يكون رقماً صحيحاً.',
            'rating.min' => 'التقييم يجب أن يكون على الأقل 1.',
            'rating.max' => 'التقييم يجب أن يكون على الأكثر 5.',

            'comment.string' => 'التعليق يجب أن يكون نصاً.',
            'comment.max' => 'التعليق لا يجب أن يتجاوز 1000 حرف.',
        ];
    }
}