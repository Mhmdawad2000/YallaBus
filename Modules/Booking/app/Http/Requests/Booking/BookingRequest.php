<?php

namespace Modules\Booking\Http\Requests\Booking;

use Modules\Trip\Models\Trip;
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
        $tripId = $this->input('trip_id'); // أو $this->route('trip_id') إذا كان موجودًا في المسار
        $busId = null;

        if ($tripId) {
            $trip = Trip::find($tripId);
            $busId = $trip?->bus_id; // يستخدم null safe operator لتجنب الخطأ إن لم توجد الرحلة
        }

        return [
            'trip_id' => ['required', 'exists:trips,id'],
            'status' => ['sometimes', 'in:pending,confirmed,cancelled,completed'],
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
            'seats' => ['required', 'array', 'min:1'],
            'seats.*' => [
                'required',
                'integer',
                Rule::exists('seats', 'id')->where(function ($query) use ($busId) {
                    if ($busId) {
                        $query->where('bus_id', $busId);
                    }

                    // شرط يستبعد المقاعد الموجودة في booking_seats مع حالة غير مكتملة
                    $query->whereNotIn('id', function ($subQuery) {
                        $subQuery->select('seat_id')
                            ->from('booking_seats')
                            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                            ->where('bookings.status', '!=', 'completed')
                            ->where('bookings.status', '!=', 'cancelled');
                    });
                }),
            ],
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

            'seats.required' => 'يجب اختيار مقاعد واحدة على الأقل',
            'seats.array' => 'المقاعد يجب أن تكون مصفوفة',
            'seats.min' => 'يجب اختيار مقاعد واحدة على الأقل',

            'seats.*.required' => 'يجب تحديد كل مقعد',
            'seats.*.integer' => 'يجب أن يكون كل مقعد رقم صحيح',
            'seats.*.exists' => 'أحد المقاعد المحددة غير موجودة أو غير متاحة للحجز',
        ];
    }

}