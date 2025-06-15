<?php

namespace Modules\Settings\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency' => 'required|string|max:30',
            'symbol' => 'required|string|max:4',
            'display' => 'required|in:symbol,name',
            'exchange_rate' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'currency.required' => 'حقل العملة مطلوب.',
            'currency.string' => 'يجب أن يكون حقل العملة نصاً.',
            'currency.max' => 'يجب ألا يتجاوز حقل العملة 30 حرفًا.',

            'symbol.required' => 'حقل الرمز مطلوب.',
            'symbol.string' => 'يجب أن يكون حقل الرمز نصاً.',
            'symbol.max' => 'يجب ألا يتجاوز حقل الرمز 4 أحرف.',

            'display.required' => 'حقل طريقة العرض مطلوب.',
            'display.in' => 'قيمة طريقة العرض يجب أن تكون إما "symbol" أو "name".',

            'exchange_rate.required' => 'حقل سعر الصرف مطلوب.',
            'exchange_rate.numeric' => 'يجب أن يكون سعر الصرف رقماً.',
        ];
    }

}
