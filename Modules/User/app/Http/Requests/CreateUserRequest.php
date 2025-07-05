<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
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
            'role_id' => 'required|exists:roles,id',
            'company_name' => 'required_if:role_id,2|string|max:255',
            'company_description' => 'required_if:role_id,2|string|max:1000',
            'company_contact_email' => 'required_if:role_id,2|email|max:255|unique:companies,contact_email',
            'company_contact_phone' => 'required_if:role_id,2|string|max:20|unique:companies,contact_phone',
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
            'password.string' => 'كلمة المرور يجب أن تكون نصًا.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 6 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حرف صغير وحرف كبير ورقم ورمز خاص على الأقل.',

            'code_phone.required' => 'رمز الاتصال مطلوب.',
            'code_phone.string' => 'رمز الاتصال يجب أن يكون نصًا.',
            'code_phone.max' => 'رمز الاتصال لا يجب أن يتجاوز 10 أحرف.',
            'code_phone.regex' => 'رمز الاتصال غير صالح. يجب أن يبدأ بـ + ويتبعه من 2 إلى 5 أرقام.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
            'phone.digits_between' => 'رقم الهاتف يجب أن يتكون من 8 إلى 12 رقمًا.',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',

            'city_id.required' => 'المدينة مطلوبة.',
            'city_id.exists' => 'المدينة المختارة غير موجودة.',

            'role_id.required' => 'الدور مطلوب.',
            'role_id.exists' => 'الدور المختار غير موجود.',

            'company_name.required_if' => 'اسم الشركة مطلوب عند اختيار نوع الحساب شركة.',
            'company_name.string' => 'اسم الشركة يجب أن يكون نصًا.',
            'company_name.max' => 'اسم الشركة لا يجب أن يتجاوز 255 حرفًا.',

            'company_description.required_if' => 'وصف الشركة مطلوب عند اختيار نوع الحساب شركة.',
            'company_description.string' => 'وصف الشركة يجب أن يكون نصًا.',
            'company_description.max' => 'وصف الشركة لا يجب أن يتجاوز 1000 حرف.',

            'company_contact_email.required_if' => 'البريد الإلكتروني للتواصل مطلوب.',
            'company_contact_email.email' => 'يجب أن يكون البريد الإلكتروني للتواصل صالحًا.',
            'company_contact_email.max' => 'البريد الإلكتروني للتواصل لا يجب أن يتجاوز 255 حرفًا.',
            'company_contact_email.unique' => 'البريد الإلكتروني للتواصل مستخدم بالفعل.',

            'company_contact_phone.required_if' => 'رقم الهاتف للتواصل مطلوب عند اختيار نوع الحساب شركة.',
            'company_contact_phone.max' => 'الهاتف للتواصل لا يجب أن يتجاوز 255 حرفًا.',
            'company_contact_phone.unique' => 'الهاتف للتواصل مستخدم بالفعل.',
        ];
    }

}
