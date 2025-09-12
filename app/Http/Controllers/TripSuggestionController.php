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
            $preferences = $this->analyzeUserPreferences($user);
            Log::info("awad", [$preferences]);

            $suggestedTrips = $this->findSuggestedTripsWithCosine($user, $preferences);
            return $this->successResponse($suggestedTrips, 200, 'تم اقتراح الرحلات بنجاح');
        } catch (Exception $e) {
            Log::error('TripSuggestionController@getSuggestedTrips', [$e->getMessage()]);
            return $this->errorResponse([], 400, 'حدث خطأ مفاجئ إثناء اقتراح الرحلات');
        }
    }

    /**
     * Analyze user preferences with counts
     */
    private function analyzeUserPreferences(User $user)
    {
        // تحليل التفضيلات من الحجوزات
        $preferences = [
            'companies' => [],
            'routes' => [],
            'times' => [
                'morning' => 0,
                'afternoon' => 0,
                'evening' => 0,
                'night' => 0
            ],
            'travel_frequency' => 0
        ];

        $pastBookings = Booking::with('trip')
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->get();

        $preferences['travel_frequency'] = $pastBookings->count();

        if ($pastBookings->isEmpty()) {
            return $preferences;
        }

        foreach ($pastBookings as $booking) {
            if ($booking->trip) {
                $trip = $booking->trip;
                $companyId = $trip->company_id;
                $preferences['companies'][$companyId] = ($preferences['companies'][$companyId] ?? 0) + 1;

                $routeKey = $trip->departure_city_id . '-' . $trip->arrival_city_id;
                $preferences['routes'][$routeKey] = ($preferences['routes'][$routeKey] ?? 0) + 1;

                $departureTime = Carbon::parse($trip->departure_time);
                $hour = (int)$departureTime->format('H');
                $timeCategory = $this->getTimeCategory($hour);
                $preferences['times'][$timeCategory]++;
            }
        }

        return $preferences;
    }

    /**
     * Categorize time into slots
     */
    private function getTimeCategory($hour)
    {
        if ($hour >= 6 && $hour < 12) return 'morning';
        if ($hour >= 12 && $hour < 18) return 'afternoon';
        if ($hour >= 18 && $hour < 24) return 'evening';
        return 'night';
    }

    /**
     * Find suggested trips using cosine similarity
     */
    private function findSuggestedTripsWithCosine(User $user, array $preferences)
    {
        $trips = Trip::with(['company', 'departureCity', 'arrivalCity', 'bus'])
            ->available()
            ->upcoming()
            ->where('departure_time', '>', now())
            ->where('available_seats', '>', 0)
            ->get();

        if ($preferences['travel_frequency'] == 0) {
            return $trips->take(10);
        }






        // بناء متجه المستخدم وفهرس الميزات
        // Build user vector and feature index
        $totalBookings = $preferences['travel_frequency'];
        $userVector = [];
        $featureIndex = [];

        // Add companies to vector
        foreach ($preferences['companies'] as $companyId => $count) {
            $featureIndex[] = 'company_' . $companyId;
            $userVector[] = $count / $totalBookings;
        }

        // Add routes to vector
        foreach ($preferences['routes'] as $routeKey => $count) {
            $featureIndex[] = 'route_' . $routeKey;
            $userVector[] = $count / $totalBookings;
        }

        // Add time categories to vector
        $timeCats = ['morning', 'afternoon', 'evening', 'night'];
        foreach ($timeCats as $cat) {
            $featureIndex[] = 'time_' . $cat;
            $userVector[] = ($preferences['times'][$cat] ?? 0) / $totalBookings;
        }
        // Precompute user vector norm
        $userNorm = sqrt(array_sum(array_map(function ($x) {
            return $x * $x;
        }, $userVector)));






        // لكل رحلة، بناء متجه الميزات
        // Calculate similarity for each trip
        $tripScores = [];
        foreach ($trips as $trip) {
            $tripFeatures = [];

            // Add company feature
            $tripFeatures['company_' . $trip->company_id] = 1;

            // Add route feature
            $routeKey = $trip->departure_city_id . '-' . $trip->arrival_city_id;
            $tripFeatures['route_' . $routeKey] = 1;

            // Add time feature
            $departureHour = (int)Carbon::parse($trip->departure_time)->format('H');
            $timeCat = $this->getTimeCategory($departureHour);
            $tripFeatures['time_' . $timeCat] = 1;

            // Build trip vector
            $tripVector = array_fill(0, count($featureIndex), 0);
            foreach ($tripFeatures as $feature => $value) {
                $pos = array_search($feature, $featureIndex);
                if ($pos !== false) {
                    $tripVector[$pos] = $value;
                }
            }

            // Compute cosine similarity
            $dotProduct = 0;
            $tripNorm = 0;
            foreach ($tripVector as $index => $value) {
                $dotProduct += $userVector[$index] * $value;
                $tripNorm += $value * $value;
            }
            $tripNorm = sqrt($tripNorm);

            if ($userNorm > 0 && $tripNorm > 0) {
                $similarity = $dotProduct / ($userNorm * $tripNorm);
            } else {
                $similarity = 0;
            }

            $tripScores[] = ['trip' => $trip, 'score' => $similarity];
        }

        // ترتيب الرحلات حسب درجة التشابه
        // Sort trips by similarity score
        usort($tripScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Return top 10 trips
        return array_map(function ($item) {
            return $item['trip'];
        }, array_slice($tripScores, 0, 10));
    }
}
