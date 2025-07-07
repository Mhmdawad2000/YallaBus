<?php

namespace Modules\Company\Http\Requests\Driver;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $driverId = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'license_number')->ignore($driverId),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ];
    }

    /**
     * Get custom error messages for validator.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نصاً.',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرفاً.',

            'license_number.required' => 'رقم الرخصة مطلوب.',
            'license_number.string' => 'رقم الرخصة يجب أن يكون نصاً.',
            'license_number.max' => 'رقم الرخصة لا يجب أن يتجاوز 255 حرفاً.',
            'license_number.unique' => 'رقم الرخصة مستخدم من قبل.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 حرفاً.',

            'photo.image' => 'الصورة يجب أن تكون من نوع صورة.',
            'photo.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, gif.',
            'photo.max' => 'الصورة لا يجب أن تتجاوز 5 ميغابايت.',
        ];
    }
}
