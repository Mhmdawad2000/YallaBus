<?php

namespace Modules\Settings\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المدينة مطلوب.',
            'name.string' => 'يجب أن يكون اسم المدينة نصاً.',
            'name.max' => 'يجب ألا يتجاوز اسم المدينة 255 حرفاً.',

            'country_id.required' => 'يجب تحديد الدولة.',
            'country_id.exists' => 'الدولة المحددة غير صالحة.',
        ];
    }
}
