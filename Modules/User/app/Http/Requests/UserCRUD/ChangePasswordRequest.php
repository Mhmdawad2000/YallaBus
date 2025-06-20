<?php

namespace Modules\User\Http\Requests\UserCRUD;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مخوّل لإجراء هذا الطلب.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق التي تنطبق على الطلب.
     */
    public function rules(): array
    {
        return [
            'old_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
        ];
    }

    /**
     * رسائل الخطأ المخصصة لقواعد التحقق.
     */
    public function messages(): array
    {
        return [
            'old_password.required' => 'كلمة المرور القديمة مطلوبة.',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'new_password.min' => 'يجب أن تكون كلمة المرور الجديدة 8 أحرف على الأقل.',
            'new_password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
            'new_password.regex' => 'يجب أن تحتوي كلمة المرور على حرف صغير وحرف كبير ورقم ورمز خاص على الأقل.',
        ];
    }
}
