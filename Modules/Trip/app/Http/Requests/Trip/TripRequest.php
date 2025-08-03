<?php

namespace Modules\Trip\Http\Requests\Trip;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tripId = $this->route('id');

        return [
            // 'company_id' => ['required', 'exists:companies,id'],
            'bus_id' => ['required', 'exists:buses,id'],
            'driver_id' => ['required', 'exists:drivers,id'],
            'departure_city_id' => ['required', 'exists:cities,id'],
            'arrival_city_id' => ['required', 'exists:cities,id', 'different:departure_city_id'],
            'departure_time' => ['required', 'date', 'after_or_equal:now'],
            'arrival_time' => ['required', 'date', 'after:departure_time'],
            'price' => ['required', 'numeric', 'min:0'],
            // 'available_seats' => ['required', 'integer', 'min:1'],
            // 'status' => ['sometimes', 'in:available,cancelled,delayed,completed'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'معرف الشركة مطلوب',
            'company_id.exists' => 'الشركة المحددة غير موجودة',

            'bus_id.required' => 'معرف الحافلة مطلوب',
            'bus_id.exists' => 'الحافلة المحددة غير موجودة',

            'driver_id.required' => 'معرف السائق مطلوب',
            'driver_id.exists' => 'السائق المحدد غير موجود',

            'departure_city_id.required' => 'مدينة المغادرة مطلوبة',
            'departure_city_id.exists' => 'مدينة المغادرة المحددة غير موجودة',

            'arrival_city_id.required' => 'مدينة الوصول مطلوبة',
            'arrival_city_id.exists' => 'مدينة الوصول المحددة غير موجودة',
            'arrival_city_id.different' => 'مدينة الوصول يجب أن تكون مختلفة عن مدينة المغادرة',

            'departure_time.required' => 'وقت المغادرة مطلوب',
            'departure_time.date' => 'وقت المغادرة يجب أن يكون تاريخًا صالحًا',
            'departure_time.after_or_equal' => 'وقت المغادرة يجب أن يكون بعد أو يساوي الوقت الحالي',

            'arrival_time.required' => 'وقت الوصول مطلوب',
            'arrival_time.date' => 'وقت الوصول يجب أن يكون تاريخًا صالحًا',
            'arrival_time.after' => 'وقت الوصول يجب أن يكون بعد وقت المغادرة',

            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقمًا',
            'price.min' => 'السعر يجب أن يكون على الأقل 0',

            'available_seats.required' => 'عدد المقاعد المتاحة مطلوب',
            'available_seats.integer' => 'عدد المقاعد المتاحة يجب أن يكون رقمًا صحيحًا',
            'available_seats.min' => 'عدد المقاعد المتاحة يجب أن يكون على الأقل 1',

            'status.in' => 'حالة الرحلة يجب أن تكون واحدة من: available, cancelled, delayed, completed',

            'notes.string' => 'الملاحظات يجب أن تكون نصًا',
            'notes.max' => 'الملاحظات لا يجب أن تتجاوز 1000 حرف',
        ];
    }
}