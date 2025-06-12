<?php

namespace Modules\Settings\Http\Requests\Permission;
use Illuminate\Foundation\Http\FormRequest;
class PermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:permissions,changeable_name,' . $this->route('id'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.unique' => 'هذا الاسم مستخدم من قبل.',
        ];
    }

}