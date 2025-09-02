<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Trip\Models\Trip;
use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TripSuggestionController extends Controller
{
    public function getSuggestedTrips(Request $request)
    {
        try {
            $user = User::find(Auth::id());

            // 1. الحصول على تفضيلات المستخدم من الحجوزات السابقة
            $preferences = $this->analyzeUserPreferences($user);

            // 2. البحث عن رحلات مناسبة بناءً على التفضيلات
            $suggestedTrips = $this->findSuggestedTrips($user, $preferences);
           
            return $this->successResponse( $suggestedTrips, 200, 'تم اقتراح الرحلات بنجاح');

        } catch (Exception $e) {
            Log::error('TripSuggestionController@getSuggestedTrips', [$e->getMessage()]);
            return $this->errorResponse([], 400, 'حدث خطأ مفاجئ إثناء اقتراح الرحلات');
        }
    }

    /**
     * تحليل تفضيلات المستخدم من الحجوزات السابقة
     */
    private function analyzeUserPreferences(User $user)
    {
        $preferences = [
            'preferred_companies' => [],
            'preferred_routes' => [],
            'preferred_times' => [],
            'travel_frequency' => 0
        ];

        // الحصول على الحجوزات السابقة للمستخدم
        $pastBookings = Booking::with('trip')
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->get();

        $preferences['travel_frequency'] = $pastBookings->count();

        if ($pastBookings->isEmpty()) {
            return $preferences;
        }

        // تحليل شركات النقل المفضلة
        $companyCounts = [];
        $routeCounts = [];
        $timeSlots = [];

        foreach ($pastBookings as $booking) {
            if ($booking->trip) {
                $trip = $booking->trip;

                // عدّ الشركات
                $companyId = $trip->company_id;
                $companyCounts[$companyId] = ($companyCounts[$companyId] ?? 0) + 1;

                // عدّ المسارات (من مدينة إلى أخرى)
                $routeKey = $trip->departure_city_id . '-' . $trip->arrival_city_id;
                $routeCounts[$routeKey] = ($routeCounts[$routeKey] ?? 0) + 1;

                // تحليل أوقات السفر
                $departureTime = Carbon::parse($trip->departure_time);
                $timeSlots[] = $departureTime->format('H:i');
            }
        }

        // تحديد الشركات المفضلة (التي حجز معها أكثر من مرة)
        arsort($companyCounts);
        $preferences['preferred_companies'] = array_keys(array_slice($companyCounts, 0, 3, true));

        // تحديد المسارات المفضلة
        arsort($routeCounts);
        $preferredRoutes = array_slice($routeCounts, 0, 5, true);

        foreach ($preferredRoutes as $routeKey => $count) {
            list($departureCityId, $arrivalCityId) = explode('-', $routeKey);
            $preferences['preferred_routes'][] = [
                'departure_city_id' => (int)$departureCityId,
                'arrival_city_id' => (int)$arrivalCityId,
                'count' => $count
            ];
        }

        // تحليل أوقات السفر المفضلة
        $preferences['preferred_times'] = $this->analyzePreferredTimes($timeSlots);

        return $preferences;
    }

    /**
     * تحليل أوقات السفر المفضلة
     */
    private function analyzePreferredTimes(array $timeSlots)
    {
        if (empty($timeSlots)) {
            return [];
        }

        $timeCategories = [
            'morning' => [6, 12],    // 6am to 12pm
            'afternoon' => [12, 18], // 12pm to 6pm
            'evening' => [18, 24],   // 6pm to 12am
            'night' => [0, 6]        // 12am to 6am
        ];

        $timeCounts = [
            'morning' => 0,
            'afternoon' => 0,
            'evening' => 0,
            'night' => 0
        ];

        foreach ($timeSlots as $time) {
            $hour = (int)explode(':', $time)[0];

            foreach ($timeCategories as $category => $range) {
                if ($hour >= $range[0] && $hour < $range[1]) {
                    $timeCounts[$category]++;
                    break;
                }
            }
        }

        // ترتيب الأوقات حسب التفضيل
        arsort($timeCounts);
        return array_keys(array_slice($timeCounts, 0, 2, true));
    }



    /**
     * البحث عن رحلات مقترحة بناءً على تفضيلات المستخدم
     */
    private function findSuggestedTrips(User $user, array $preferences)
    {
        $query = Trip::with(['company', 'departureCity', 'arrivalCity', 'bus'])
            ->available()
            ->upcoming()
            ->where('departure_time', '>', now()->addHours(2)) // رحلات بعد ساعتين على الأقل
            ->where('available_seats', '>', 0); // رحلات بها مقاعد متاحة
        // ->where('price', '<=', $user->balance); // رحلات within user's budget

        // إذا كان للمستخدم تفضيلات محددة
        if (!empty($preferences['preferred_companies'])) {
            $query->whereIn('company_id', $preferences['preferred_companies']);
        }

        // إذا كان للمستخدم مسارات مفضلة
        if (!empty($preferences['preferred_routes'])) {
            $routeConditions = function ($q) use ($preferences) {
                foreach ($preferences['preferred_routes'] as $route) {
                    $q->orWhere(function ($q2) use ($route) {
                        $q2->where('departure_city_id', $route['departure_city_id'])
                            ->where('arrival_city_id', $route['arrival_city_id']);
                    });
                }
            };

            $query->where($routeConditions);
        }

        // تطبيق تفضيلات الوقت إذا وجدت
        if (!empty($preferences['preferred_times'])) {
            $timeConditions = function ($q) use ($preferences) {
                foreach ($preferences['preferred_times'] as $timeCategory) {
                    switch ($timeCategory) {
                        case 'morning':
                            $q->orWhereTime('departure_time', '>=', '06:00:00')
                                ->whereTime('departure_time', '<', '12:00:00');
                            break;
                        case 'afternoon':
                            $q->orWhereTime('departure_time', '>=', '12:00:00')
                                ->whereTime('departure_time', '<', '18:00:00');
                            break;
                        case 'evening':
                            $q->orWhereTime('departure_time', '>=', '18:00:00')
                                ->whereTime('departure_time', '<', '24:00:00');
                            break;
                        case 'night':
                            $q->orWhereTime('departure_time', '>=', '00:00:00')
                                ->whereTime('departure_time', '<', '06:00:00');
                            break;
                    }
                }
            };

            $query->where($timeConditions);
        }

        // إذا لم يكن هناك رحلات تطابق التفضيلات، نعود برحلات عشوائية متاحة
        $suggestedTrips = $query->take(10)->get();

        if ($suggestedTrips->isEmpty()) {
            $suggestedTrips = Trip::with(['company', 'departureCity', 'arrivalCity', 'bus'])
                ->available()
                ->upcoming()
                ->where('departure_time', '>', now()->addHours(2))
                ->where('available_seats', '>', 0)
                ->where('price', '<=', $user->balance)
                ->inRandomOrder()
                ->take(5)
                ->get();
        }

        return $suggestedTrips;
    }
}
