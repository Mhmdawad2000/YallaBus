<?php

namespace Modules\Settings\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'dialing_code' => 'required|string|max:10|unique:countries,dialing_code,' . $this->route('id'),
            'currency_id' => 'required|exists:currencies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الدولة مطلوب.',
            'dialing_code.required' => 'كود الاتصال مطلوب.',
            'dialing_code.unique' => 'كود الاتصال مستخدم مسبقًا.',
            'currency_id.required' => 'يجب تحديد العملة.',
            'currency_id.exists' => 'العملة غير صالحة.',
        ];
    }
}
