<?php

namespace Modules\Settings\Http\Requests\Permission;
use Illuminate\Foundation\Http\FormRequest;
class PermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'changeable_name' => 'required|string|unique:permissions,changeable_name,' . $this->route('id'),
        ];
    }

    public function messages(): array
    {
        return [
            'changeable_name.required' => 'حقل الاسم مطلوب.',
            'changeable_name.string' => 'يجب أن يكون الاسم نصًا.',
            'changeable_name.unique' => 'هذا الاسم مستخدم من قبل.',
        ];
    }

}