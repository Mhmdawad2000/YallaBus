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
        ];
    }

    public function messages(): array
    {
        return [
            // الاسم الأول
            'first_name.string' => 'يجب أن يكون الاسم الأول نصًا',
            'first_name.max' => 'يجب ألا يتجاوز الاسم الأول 255 حرفًا',

            // الاسم الأخير
            'last_name.string' => 'يجب أن يكون الاسم الأخير نصًا',
            'last_name.max' => 'يجب ألا يتجاوز الاسم الأخير 255 حرفًا',

            // البريد الإلكتروني
            'email.email' => 'يجب إدخال بريد إلكتروني صحيح',
            'email.max' => 'يجب ألا يتجاوز البريد الإلكتروني 255 حرفًا',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',

            // كلمة المرور
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حرف كبير وحرف صغير ورقم ورمز خاص على الأقل',

            // رمز الهاتف
            'code_phone.string' => 'يجب أن يكون رمز الهاتف نصًا',
            'code_phone.max' => 'يجب ألا يتجاوز رمز الهاتف 10 أحرف',
            'code_phone.regex' => 'صيغة رمز الهاتف غير صالحة (يجب أن يبدأ ب + ويتبعه أرقام)',
            'code_phone.required_with' => 'رمز الهاتف مطلوب عند إدخال رقم الهاتف',

            // الهاتف
            'phone.numeric' => 'يجب أن يكون الهاتف رقمًا',
            'phone.digits_between' => 'يجب أن يكون الهاتف بين 8 و12 رقمًا',
            'phone.unique' => 'رقم الهاتف هذا مستخدم بالفعل',
            'phone.required_with' => 'رقم الهاتف مطلوب عند إدخال رمز الهاتف',

            // المدينة
            'city_id.exists' => 'المدينة المحددة غير صالحة',

            // الدور
            'role_id.exists' => 'الدور المحدد غير صالح',
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