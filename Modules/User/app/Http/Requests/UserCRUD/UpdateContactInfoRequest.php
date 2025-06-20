<?php

namespace Modules\User\Http\Requests\UserCRUD;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContactInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'code_phone' => [
                'required',
                'string',
                'max:10',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('phone', $this->phone);
                })->ignore($userId),
            ],
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('code_phone', $this->code_phone);
                })->ignore($userId),
            ],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',

            'code_phone.required' => 'رمز الهاتف مطلوب',
            'code_phone.unique' => 'رمز الهاتف مع رقم الهاتف مستخدمان مسبقًا',

            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مع رمز الهاتف مستخدمان مسبقًا',

        ];
    }
}