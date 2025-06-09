<?php

namespace Modules\Settings\Http\Requests\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'changeable_name' => 'nullable|string|unique:roles,changeable_name,' . $this->route('id'),
            'policy' => [
                'nullable',
                'string',
                Rule::in(array_keys(config('role_policy.policy'))),
            ],
            'permissions' => 'array|nullable',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'changeable_name.required' => 'حقل الاسم مطلوب.',
            'changeable_name.string' => 'يجب أن يكون الاسم نصًا.',
            'changeable_name.unique' => 'الاسم مستخدم من قبل.',

            'policy.required' => 'حقل السياسة مطلوب.',
            'policy.string' => 'يجب أن تكون السياسة نصًا.',
            'policy.in' => 'السياسة المحددة غير صالحة.[' . implode(', ', array_keys(config('role_policy.policy'))) . ']',

            'permissions.array' => 'يجب أن تكون الأذونات على شكل مصفوفة.',
            'permissions.*.exists' => 'أحد الأذونات المحددة أو أكثر غير موجود.',
        ];
    }

}
