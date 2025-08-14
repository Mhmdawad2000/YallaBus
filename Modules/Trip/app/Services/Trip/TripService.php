<?php

namespace Modules\Trip\Services\Trip;

use Illuminate\Http\Request;
use Modules\Trip\Models\Trip;
use Modules\User\Models\User;
use Modules\Company\Models\Bus;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Modules\Trip\Http\Requests\Trip\TripRequest;
use Modules\Booking\Models\BookingSeat;

class TripService implements TripInterface
{
    public function index(Request $request)
    {
        try {
            $trips = Trip::filter($request)
                ->where('status', 'available')
                ->with(['company', 'bus', 'driver', 'departureCity', 'arrivalCity'])
                ->paginate($request->input('per_page', 10));

            return [true, $trips, 200, 'تم جلب الرحلات بنجاح'];
        } catch (\Exception $e) {
            Log::error('TripService@index: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب الرحلات'];
        }
    }
    public function myTrips(Request $request)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }
            $trips = Trip::filter($request)
                ->where('company_id', $company->id)
                ->with(['company', 'bus', 'driver', 'departureCity', 'arrivalCity'])
                ->paginate($request->input('per_page', 10));

            return [true, $trips, 200, 'تم جلب الرحلات بنجاح'];
        } catch (\Exception $e) {
            Log::error('TripService@myTrips: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء جلب الرحلات'];
        }
    }

    public function store(TripRequest $request)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }
            $data = $request->validated();
            $data['company_id'] = $company->id;
            $bus = Bus::withCount('seats')->find($data['bus_id']);
            if (!$bus) {
                return [false, [], 404, 'الباص غير موجود.'];
            }
            $data['available_seats'] = $bus->seats_count;
            $trip = Trip::create($data);

            return [true, $trip, 201, 'تم إنشاء الرحلة بنجاح'];
        } catch (\Exception $e) {
            Log::error('TripService@store: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء إنشاء الرحلة'];
        }
    }

    public function show($id)
    {
        try {
            $trip = Trip::with(['company', 'bus', 'driver', 'departureCity', 'arrivalCity'])->find($id);

            if (!$trip) {
                return [false, [], 404, 'الرحلة غير موجودة'];
            }

            return [true, $trip, 200, 'تم جلب بيانات الرحلة بنجاح'];
        } catch (\Exception $e) {
            Log::error('TripService@show: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء عرض بيانات الرحلة'];
        }
    }

    public function update(TripRequest $request, $id)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }
            $trip = Trip::find($id);

            if (!$trip) {
                return [false, [], 404, 'الرحلة غير موجودة'];
            }
            if ($trip->company_id !== $company->id) {
                return [false, [], 403, 'ليس لديك صلاحية على هذه الرحلة'];
            }
            $trip->update($request->validated());

            return [true, $trip, 200, 'تم تحديث بيانات الرحلة بنجاح'];
        } catch (\Exception $e) {
            Log::error('TripService@update: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء تحديث بيانات الرحلة'];
        }
    }

    public function destroy($id)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }

            $trip = Trip::withCount('bookings')->find($id);
            if (!$trip) {
                return [false, [], 404, 'الرحلة غير موجودة'];
            }
            if ($trip->company_id !== $company->id) {
                return [false, [], 403, 'ليس لديك صلاحية على هذه الرحلة'];
            }

            if ($trip->bookings_count > 0) {
                return [false, [], 400, 'لا يمكن حذف الرحلة لأنها تحتوي على حجوزات.'];
            }
            $trip->delete();
            return [true, null, 200, 'تم حذف الرحلة بنجاح'];
        } catch (\Exception $e) {
            Log::error('TripService@destroy: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء حذف الرحلة'];
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $error = $this->checkRole();
            if ($error) {
                return $error;
            }

            $company = $this->getCompany();
            if (!$company) {
                return [false, [], 404, 'الشركة غير موجودة.'];
            }
            $trip = Trip::find($id);

            if (!$trip) {
                return [false, [], 404, 'الرحلة غير موجودة'];
            }

            if (!in_array($status, ['available', 'cancelled', 'delayed', 'completed'])) {
                return [false, [], 400, 'حالة الرحلة غير صالحة'];
            }

            // Handle trip cancellation with proper booking updates
            if ($status == 'cancelled') {
                $bookings = Booking::where('trip_id', $trip->id)
                    ->where('status', '!=', 'cancelled')
                    ->with('user')
                    ->get();

                foreach ($bookings as $booking) {
                    // Update booking status
                    $booking->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancellation_reason' => 'تم إلغاء الرحلة من قبل الشركة'
                    ]);

                    // Refund user balance
                    $booking->user->addBalance(
                        $booking->total_price,
                        'استرداد مبلغ الحجز - إلغاء الرحلة',
                        $booking->id
                    );
                }

                // Update available seats count
                $trip->update(['available_seats' => $trip->bus->seats()->count()]);
            }

            if ($status == 'completed') {
                Booking::where('trip_id', $trip->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'completed']);
            }

            $trip->update(['status' => $status]);

            return [true, $trip, 200, 'تم تحديث حالة الرحلة بنجاح'];
        } catch (\Exception $e) {
            Log::error('TripService@updateStatus: ' . $e->getMessage());
            return [false, null, 500, 'حدث خطأ أثناء تحديث حالة الرحلة'];
        }
    }

    /**********************
     *  Helper Functions  *
     **********************/
    private function getCompany()
    {
        try {
            $user = User::find(Auth::id());
            return $user?->company;
        } catch (\Exception $e) {
            Log::error('BusService@getCompany: ' . $e->getMessage());
            return null;
        }
    }

    private function checkRole()
    {
        $userRole = User::find(Auth::id())->role->name ?? null;
        if ($userRole !== 'company-admin') {
            return [false, [], 403, 'غير مصرح لك بالوصول.'];
        }
        return null;
    }

    /**
     * Update available seats count for a trip
     */
    public function updateAvailableSeats($tripId)
    {
        try {
            $trip = Trip::with('bus.seats')->find($tripId);
            if (!$trip) {
                return false;
            }

            // Count booked seats for this trip
            $bookedSeatsCount = BookingSeat::whereHas('booking', function ($query) use ($tripId) {
                $query->where('trip_id', $tripId)
                    ->where('status', '!=', 'cancelled');
            })->count();

            // Calculate available seats
            $totalSeats = $trip->bus->seats()->count();
            $availableSeats = $totalSeats - $bookedSeatsCount;

            $trip->update(['available_seats' => max(0, $availableSeats)]);

            return true;
        } catch (\Exception $e) {
            Log::error('TripService@updateAvailableSeats: ' . $e->getMessage());
            return false;
        }
    }
}