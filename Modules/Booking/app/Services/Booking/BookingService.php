<?php

namespace Modules\Booking\Services\Booking;

use Illuminate\Http\Request;
use Modules\Trip\Models\Trip;
use Modules\User\Models\User;
use Modules\Company\Models\Seat;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $totalPrice = 0;
            $trip = Trip::findOrFail($data['trip_id']);
            $seats = Seat::whereIn('id', $data['seats'])->get()->keyBy('id');

            // Check if seats exist and are available
            foreach ($data['seats'] as $seatId) {
                $seat = $seats->get($seatId);
                if (!$seat) {
                    DB::rollBack();
                    return [false, [], 400, "المقعد $seatId غير موجود"];
                }

                // Check if seat is already booked for this trip
                if (
                    BookingSeat::where('seat_id', $seatId)
                        ->whereHas('booking', function ($q) use ($trip) {
                            $q->where('trip_id', $trip->id)
                                ->where('status', '!=', 'cancelled');
                        })->exists()
                ) {
                    DB::rollBack();
                    return [false, [], 400, "المقعد $seatId محجوز بالفعل"];
                }

                $seatPrice = $seat->class == 'VIP' ? $trip->price * 1.2 : $trip->price;
                $totalPrice += $seatPrice;
            }

            $user = User::find(Auth::id());
            if ($user->balance < $totalPrice) {
                DB::rollBack();
                return [false, [], 400, 'رصيدك غير كافي'];
            }

            $bookingData = [
                'trip_id' => $data['trip_id'],
                'user_id' => $user->id,
                'booking_reference' => $this->generateBookingReference(),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'cancellation_reason' => $data['cancellation_reason'] ?? null,
            ];

            // Create the booking
            $booking = Booking::create($bookingData);

            // Create booking_seats records with correct pricing
            foreach ($data['seats'] as $seatId) {
                $seat = $seats->get($seatId);
                $seatPrice = $seat->class == 'VIP' ? $trip->price * 1.2 : $trip->price;

                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'price' => $seatPrice, // Use the correct seat price
                ]);
            }

            // Deduct balance with proper parameters
            $deductionResult = $user->deductBalance($totalPrice, 'خصم مبلغ الحجز', $booking->id);
            if (!$deductionResult) {
                DB::rollBack();
                return [false, [], 400, 'فشل في خصم المبلغ من الرصيد'];
            }
            
            // update booking status
            $booking->update(['status' => 'confirmed']);

            DB::commit();
            return [true, $booking, 201, 'تم إنشاء الحجز بنجاح'];
        } catch (\Exception $e) {
            DB::rollBack();
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
        DB::beginTransaction();
        try {
            $booking = Booking::with(['user', 'trip'])->find($id);

            if (!$booking) {
                DB::rollBack();
                return [false, [], 404, 'الحجز غير موجود'];
            }

            // Check if user can cancel this booking
            if ($booking->user_id !== Auth::id()) {
                DB::rollBack();
                return [false, [], 403, 'غير مصرح لك بإلغاء هذا الحجز'];
            }

            // Check if booking is already cancelled
            if ($booking->status === 'cancelled') {
                DB::rollBack();
                return [false, [], 400, 'الحجز ملغي بالفعل'];
            }

            // Check if trip has already started (optional - you might want to add this logic)
            if ($booking->trip->departure_time <= now()) {
                DB::rollBack();
                return [false, [], 400, 'لا يمكن إلغاء الحجز بعد بدء الرحلة'];
            }

            // Calculate refund amount (you might want to add cancellation fees logic)
            $refundAmount = $booking->total_price;

            // Update booking status
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // Refund the user's balance
            $user = $booking->user;
            $refundResult = $user->addBalance($refundAmount * 0.9, 'استرداد مبلغ الحجز الملغي', $booking->id);

            if (!$refundResult) {
                DB::rollBack();
                return [false, [], 400, 'فشل في استرداد المبلغ'];
            }

            DB::commit();
            return [true, $booking, 200, 'تم إلغاء الحجز بنجاح وتم استرداد 90% من المبلغ'];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BookingService@cancel: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إلغاء الحجز'];
        }
    }
    // public function status($id, Request $request)
    // {
    //     try {
    //         $booking = Booking::find($id);

    //         if (!$booking) {
    //             return [false, [], 404, 'الحجز غير موجود'];
    //         }

    //         $currentUserId = Auth::id();

    //         $isAdmin = optional($booking->trip->company->admin)->id === $currentUserId;

    //         if (!$isAdmin) {
    //             return [false, [], 403, 'غير مصرح لك تعديل هذا الحجز'];
    //         }

    //         $booking->update([
    //             'status' => $request->status,
    //         ]);

    //         return [true, $booking, 200, 'تم إلغاء الحجز بنجاح'];
    //     } catch (\Exception $e) {
    //         Log::error('BookingService@cancel: ' . $e->getMessage());
    //         return [false, null, 500, 'حدث خطأ أثناء إلغاء الحجز'];
    //     }
    // }

    private function generateBookingReference()
    {
        return 'BOOK-' . strtoupper(uniqid());
    }
}