<?php

namespace Modules\User\Http\Requests\UserCRUD;

use Illuminate\Foundation\Http\FormRequest;

class AvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // 5MB
        ];
    }
    public function messages(): array
    {
        return [
            'avatar.image' => 'الملف المرفوع يجب أن يكون صورة',
            'avatar.mimes' => 'صيغة الصورة يجب أن تكون jpg أو jpeg أو png',
            'avatar.max' => 'حجم الصورة يجب ألا يتجاوز 5 ميغابايت',
        ];
    }
}
