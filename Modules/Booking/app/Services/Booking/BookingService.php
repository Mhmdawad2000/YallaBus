<?php

namespace Modules\Booking\Services\Booking;

use Illuminate\Http\Request;
use Modules\Booking\Models\Booking;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Http\Requests\Booking\BookingRequest;

class BookingService implements BookingInterface
{
    public function index(Request $request)
    {
        try {
            $bookings = Booking::filter($request)
                ->with(['user', 'trip'])
                ->paginate($request->input('per_page', 10));

            return [true, $bookings, 200, 'تم جلب الحجوزات بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@index: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب الحجوزات'];
        }
    }

    public function store(BookingRequest $request)
    {
        try {
            $data = $request->validated();
            $data['booking_reference'] = $this->generateBookingReference();
            $booking = Booking::create($data);

            return [true, $booking, 201, 'تم إنشاء الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@store: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إنشاء الحجز'];
        }
    }

    public function show($id)
    {
        try {
            $booking = Booking::with(['user', 'trip'])->find($id);

            if (!$booking) {
                return [false, [], 404, 'الحجز غير موجود'];
            }

            return [true, $booking, 200, 'تم جلب بيانات الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@show: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء عرض بيانات الحجز'];
        }
    }

    public function update(BookingRequest $request, $id)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return [false, [], 404, 'الحجز غير موجود'];
            }

            $booking->update($request->validated());

            return [true, $booking, 200, 'تم تحديث بيانات الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@update: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء تحديث بيانات الحجز'];
        }
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return [false, [], 404, 'الحجز غير موجود'];
            }

            $booking->delete();

            return [true, null, 200, 'تم حذف الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@destroy: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء حذف الحجز'];
        }
    }

    public function cancel($id, $reason)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return [false, [], 404, 'الحجز غير موجود'];
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $reason
            ]);

            return [true, $booking, 200, 'تم إلغاء الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@cancel: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إلغاء الحجز'];
        }
    }

    private function generateBookingReference()
    {
        return 'BOOK-' . strtoupper(uniqid());
    }
}