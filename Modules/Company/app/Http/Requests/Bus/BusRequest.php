<?php

namespace Modules\Company\Http\Requests\Bus;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $busId = $this->route('id');

        return [
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('buses', 'plate_number')->ignore($busId),
            ],
            'model' => ['required', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'type' => ['required', 'in:VIP,ECONOMIC'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'plate_number.required' => 'رقم اللوحة مطلوب.',
            'plate_number.string' => 'رقم اللوحة يجب أن يكون نصاً.',
            'plate_number.max' => 'رقم اللوحة يجب ألا يتجاوز 20 حرفاً.',
            'plate_number.unique' => 'رقم اللوحة هذا مستخدم من قبل.',

            'model.required' => 'نوع الحافلة مطلوب.',
            'model.string' => 'نوع الحافلة يجب أن يكون نصاً.',
            'model.max' => 'نوع الحافلة يجب ألا يتجاوز 100 حرف.',

            'capacity.required' => 'السعة مطلوبة.',
            'capacity.integer' => 'السعة يجب أن تكون رقماً صحيحاً.',
            'capacity.min' => 'السعة يجب أن تكون على الأقل 1.',
            'capacity.max' => 'السعة يجب ألا تتجاوز 100.',

            'type.required' => 'النوع مطلوب.',
            'type.in' => 'النوع يجب أن يكون VIP أو ECONOMIC فقط.',

            'amenities.array' => 'الميزات يجب أن تكون على شكل مصفوفة.',
            'amenities.*.string' => 'كل ميزة يجب أن تكون نصاً.',
            'amenities.*.max' => 'كل ميزة يجب ألا تتجاوز 50 حرفاً.',
        ];
    }

}