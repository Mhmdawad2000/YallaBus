<?php

namespace Modules\Settings\Http\Requests\Role;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AddRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'changeable_name' => 'required|string|unique:roles,changeable_name,' . $this->route('id'),
            'can_delete' => 'required|boolean|in:1,0',
            'policy' => [
                'required',
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

            'can_delete.required' => 'حقل إمكانية الحذف مطلوب.',
            'can_delete.boolean' => 'يجب أن تكون قيمة إمكانية الحذف true أو false.',
            'can_delete.in' => 'يجب أن تكون قيمة إمكانية الحذف إما 1 أو 0.',

            'policy.required' => 'حقل السياسة مطلوب.',
            'policy.string' => 'يجب أن تكون السياسة نصًا.',
            'policy.in' => 'السياسة المحددة غير صالحة.[' . implode(', ', array_keys(config('role_policy.policy'))) . ']',

            'permissions.array' => 'يجب أن تكون الأذونات على شكل مصفوفة.',
            'permissions.*.exists' => 'أحد الأذونات المحددة أو أكثر غير موجود.',
        ];
    }

}
