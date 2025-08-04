<?php

namespace Modules\Company\Http\Requests\Seat;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $seatId = $this->route('id');
        $busId = $this->input('bus_id');

        return [
            'bus_id' => ['required', 'exists:buses,id'],
            'seat_number' => [
                'required',
                'string',
                'max:10',
                Rule::unique('seats', 'seat_number')
                    ->where('bus_id', $busId)
                    ->ignore($seatId),
            ],
            'class' => ['required', 'in:VIP,ECONOMIC'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'bus_id.required' => 'رقم الحافلة مطلوب.',
            'bus_id.exists' => 'الحافلة المحددة غير موجودة.',

            'seat_number.required' => 'رقم المقعد مطلوب.',
            'seat_number.string' => 'يجب أن يكون رقم المقعد نصًا.',
            'seat_number.max' => 'يجب ألا يزيد رقم المقعد عن 10 أحرف.',
            'seat_number.unique' => 'رقم المقعد موجود مسبقًا في نفس الحافلة.',

            'class.required' => 'درجة المقعد مطلوبة.',
            'class.in' => 'يجب أن تكون درجة المقعد VIP أو ECONOMIC فقط.',

            'is_available.boolean' => 'قيمة التوفر يجب أن تكون true أو false.',
        ];
    }
}
