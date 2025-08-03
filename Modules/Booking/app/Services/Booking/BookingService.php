<?php

namespace Modules\Booking\Services\Booking;

use Illuminate\Http\Request;
use Modules\Trip\Models\Trip;
use Modules\Company\Models\Seat;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\BookingSeat;
use Modules\Booking\Http\Requests\Booking\BookingRequest;

class BookingService implements BookingInterface
{
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();

            $bookings = Booking::filter($request)
                ->whereHas('trip.company.admin', function ($query) use ($userId) {
                    $query->where('id', $userId);
                })
                ->with(['user', 'trip', 'bookingSeats'])
                ->paginate($request->input('per_page', 10));

            return [true, $bookings, 200, 'تم جلب الحجوزات بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@index: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب الحجوزات'];
        }
    }


    public function meIndex(Request $request)
    {
        try {
            $bookings = Booking::filter($request)
                ->where(['user_id' => Auth::id()])
                ->with(['user', 'trip', 'bookingSeats'])
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

            // Get the trip and ensure it exists
            $trip = Trip::findOrFail($data['trip_id']);

            // Prepare base booking data
            $bookingData = [
                'trip_id' => $data['trip_id'],
                'user_id' => Auth::id(),
                'booking_reference' => $this->generateBookingReference(),
                'total_price' => 0,
                'status' => 'pending',
                'cancellation_reason' => $data['cancellation_reason'] ?? null,
            ];

            // Create the booking
            $booking = Booking::create($bookingData);

            $totalPrice = 0;

            // Create booking_seats records
            foreach ($data['seats'] as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'price' => $trip->price,
                ]);

                $totalPrice += $trip->price;
            }

            // Update total price
            $booking->update(['total_price' => $totalPrice]);

            return [true, $booking, 201, 'تم إنشاء الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@store: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إنشاء الحجز'];
        }
    }
    public function show($id)
    {
        try {
            $booking = Booking::with(['user', 'trip.company.admin'])->find($id);

            if (!$booking) {
                return [false, [], 404, 'الحجز غير موجود'];
            }

            $currentUserId = Auth::id();

            $isOwner = $booking->user_id === $currentUserId;
            $isAdmin = optional($booking->trip->company->admin)->id === $currentUserId;

            if (!($isOwner || $isAdmin)) {
                return [false, [], 403, 'غير مصرح لك بعرض هذا الحجز'];
            }

            return [true, $booking, 200, 'تم جلب بيانات الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@show: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء عرض بيانات الحجز'];
        }
    }

    // public function update(BookingRequest $request, $id)
    // {
    //     try {
    //         $booking = Booking::find($id);

    //         if (!$booking) {
    //             return [false, [], 404, 'الحجز غير موجود'];
    //         }

    //         $booking->update($request->validated());

    //         return [true, $booking, 200, 'تم تحديث بيانات الحجز بنجاح'];
    //     } catch (\Exception $e) {
    //         Log::error('BookingService@update: ' . $e->getMessage());
    //         return [false, null, 500, 'حدث خطأ أثناء تحديث بيانات الحجز'];
    //     }
    // }

    public function destroy($id)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return [false, [], 404, 'الحجز غير موجود'];
            }
            $currentUserId = Auth::id();
            $isOwner = $booking->user_id === $currentUserId;
            $isAdmin = optional($booking->trip->company->admin)->id === $currentUserId;

            if (!($isOwner || $isAdmin)) {
                return [false, [], 403, 'غير مصرح لك بحذف هذا الحجز'];
            }
            $booking->delete();

            return [true, null, 200, 'تم حذف الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@destroy: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء حذف الحجز'];
        }
    }

    public function cancel($id, Request $request)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return [false, [], 404, 'الحجز غير موجود'];
            }

            if ($booking->user_id !== Auth::id()) {
                return [false, [], 403, 'غير مصرح لك بإلغاء هذا الحجز'];
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            return [true, $booking, 200, 'تم إلغاء الحجز بنجاح'];
        } catch (\Exception $e) {
            Log::error('BookingService@cancel: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إلغاء الحجز'];
        }
    }
    public function status($id, Request $request)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return [false, [], 404, 'الحجز غير موجود'];
            }

            $currentUserId = Auth::id();

            $isAdmin = optional($booking->trip->company->admin)->id === $currentUserId;

            if (!$isAdmin) {
                return [false, [], 403, 'غير مصرح لك تعديل هذا الحجز'];
            }

            $booking->update([
                'status' => $request->status,
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