<?php

namespace Modules\User\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You may implement your own logic here
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],

            'code_phone' => 'required|string|max:10|regex:/^\+\d{2,5}$/',
            'phone' => 'required|numeric|digits_between:8,12',
            'city_id' => 'required|exists:cities,id',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $codePhone = $this->input('code_phone');
            $phone = $this->input('phone');

            if ($codePhone && $phone) {
                $exists = DB::table('users')
                    ->where('code_phone', $codePhone)
                    ->where('phone', $phone)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('phone', 'رقم الهاتف مع رمز الاتصال مستخدم مسبقًا.');
                }
            }
        });
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب.',
            'first_name.string' => 'الاسم الأول يجب أن يكون نصًا.',
            'first_name.max' => 'الاسم الأول لا يجب أن يتجاوز 255 حرفًا.',

            'last_name.required' => 'الاسم الأخير مطلوب.',
            'last_name.string' => 'الاسم الأخير يجب أن يكون نصًا.',
            'last_name.max' => 'الاسم الأخير لا يجب أن يتجاوز 255 حرفًا.',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرفًا.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 6 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حرف صغير وحرف كبير ورقم ورمز خاص على الأقل.',


            'code_phone.string' => 'رمز الهاتف يجب أن يكون نصًا.',
            'code_phone.max' => 'رمز الهاتف لا يجب أن يتجاوز 10 أحرف.',
            'code_phone.regex' => 'السابقة الخلوية غير مقبولة.',

            'phone.string' => 'رقم الهاتف يجب أن يكون نصًا.',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 حرفًا.',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',

            'city_id.required' => 'المدينة مطلوبة.',
            'city_id.exists' => 'المدينة المختارة غير موجودة.',

            'role_id.required' => 'الدور مطلوب.',
            'role_id.exists' => 'الدور المختار غير موجود.',
        ];
    }
}
