<?php

namespace Modules\User\Http\Requests;

use Modules\User\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    protected $user;

    public function authorize()
    {
        $userId = $this->route('userId');
        $this->user = User::find($userId);

        if (!$this->user) {
            throw new HttpResponseException(response()->json([
                'data' => [],
                'status' => 404,
                'message' => 'المستخدم غير موجود',
            ], 404));
        }

        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('userId');

        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
            'code_phone' => 'nullable|required_with:phone|string|max:10|regex:/^\+\d{2,5}$/',
            'phone' => [
                'nullable',
                'required_with:code_phone',
                'numeric',
                'digits_between:8,12',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('code_phone', $this->input('code_phone'));
                })->ignore($userId),
            ],
            'city_id' => 'nullable|exists:cities,id',
            'role_id' => 'nullable|exists:roles,id',
            'company_name' => 'required_if:role_id,2|string|max:255',
            'company_logo' => 'nullable|image|max:5120',
            'company_description' => 'required_if:role_id,2|string|max:1000',
            'company_contact_email' => [
                'required_if:role_id,2',
                'email',
                'max:255',
                Rule::unique('companies', 'contact_email')->ignore($userId, 'admin_id')
            ],

            'company_contact_phone' => [
                'required_if:role_id,2',
                'string',
                'max:20',
                Rule::unique('companies', 'contact_phone')->ignore($userId, 'admin_id')
            ],
        ];
    }

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

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (!$this->hasAtLeastOneField()) {
                $validator->errors()->add('fields', 'يجب تقديم حقل واحد على الأقل للتحديث');
            }
        });
    }

    public function hasAtLeastOneField(): bool
    {
        $data = $this->only([
            'first_name',
            'last_name',
            'email',
            'code_phone',
            'phone',
            'role_id',
            'city_id',
            'password'
        ]);

        return !empty(array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        }));
    }
}