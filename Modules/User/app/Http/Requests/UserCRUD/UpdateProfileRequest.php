<?php

namespace Modules\User\Http\Requests\UserCRUD;

use Modules\User\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = User::find(Auth::id());
        $data = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'exists:cities,id'],
        ];
        if ($user->role_id == 2) {
            $data2 = [
                'company_name' => 'required|string|max:255',
                'company_description' => 'required|string|max:1000',
                'company_contact_email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('companies', 'contact_email')->ignore($user->id, 'admin_id')
                ],

                'company_contact_phone' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('companies', 'contact_phone')->ignore($user->id, 'admin_id')
                ],
            ];
            $data = array_merge($data, $data2);
        }
        return $data;
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب',
            'first_name.string' => 'الاسم الأول يجب أن يكون نصًا',
            'first_name.max' => 'الاسم الأول يجب ألا يتجاوز 255 حرفًا',

            'last_name.required' => 'الاسم الأخير مطلوب',
            'last_name.string' => 'الاسم الأخير يجب أن يكون نصًا',
            'last_name.max' => 'الاسم الأخير يجب ألا يتجاوز 255 حرفًا',

            'city_id.required' => 'المدينة مطلوبة',
            'city_id.exists' => 'المدينة غير موجودة في النظام',

            'company_name.required' => 'اسم الشركة مطلوب عند اختيار نوع الحساب شركة.',
            'company_name.string' => 'اسم الشركة يجب أن يكون نصًا.',
            'company_name.max' => 'اسم الشركة لا يجب أن يتجاوز 255 حرفًا.',

            'company_description.required' => 'وصف الشركة مطلوب عند اختيار نوع الحساب شركة.',
            'company_description.string' => 'وصف الشركة يجب أن يكون نصًا.',
            'company_description.max' => 'وصف الشركة لا يجب أن يتجاوز 1000 حرف.',

            'company_contact_email.required' => 'البريد الإلكتروني للتواصل مطلوب.',
            'company_contact_email.email' => 'يجب أن يكون البريد الإلكتروني للتواصل صالحًا.',
            'company_contact_email.max' => 'البريد الإلكتروني للتواصل لا يجب أن يتجاوز 255 حرفًا.',
            'company_contact_email.unique' => 'البريد الإلكتروني للتواصل مستخدم بالفعل.',

            'company_contact_phone.required' => 'رقم الهاتف للتواصل مطلوب عند اختيار نوع الحساب شركة.',
            'company_contact_phone.max' => 'الهاتف للتواصل لا يجب أن يتجاوز 255 حرفًا.',
            'company_contact_phone.unique' => 'الهاتف للتواصل مستخدم بالفعل.',
        ];
    }
}
