<?php

namespace Modules\Booking\Http\Requests\Booking;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookingId = $this->route('id');

        return [
            'user_id' => ['required', 'exists:users,id'],
            'trip_id' => ['required', 'exists:trips,id'],
            // 'total_price' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:pending,confirmed,cancelled,completed'],
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'معرف المستخدم مطلوب',
            'user_id.exists' => 'المستخدم المحدد غير موجود',

            'trip_id.required' => 'معرف الرحلة مطلوب',
            'trip_id.exists' => 'الرحلة المحددة غير موجودة',

            'total_price.required' => 'السعر الإجمالي مطلوب',
            'total_price.numeric' => 'السعر الإجمالي يجب أن يكون رقماً',
            'total_price.min' => 'السعر الإجمالي يجب أن يكون على الأقل 0',

            'status.in' => 'حالة الحجز يجب أن تكون واحدة من: pending, confirmed, cancelled, completed',

            'cancellation_reason.string' => 'سبب الإلغاء يجب أن يكون نصاً',
            'cancellation_reason.max' => 'سبب الإلغاء لا يجب أن يتجاوز 500 حرف',
        ];
    }
}