<?php

namespace Modules\Trip\Models;

use App\Models\BaseModel;
use Modules\Company\Models\Bus;
use Modules\Settings\Models\City;
use Modules\Company\Models\Driver;
use Modules\Booking\Models\Booking;
use Modules\Company\Models\Company;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Trip extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'company_id',
        'bus_id',
        'driver_id',
        'departure_city_id',
        'arrival_city_id',
        'departure_time',
        'arrival_time',
        'price',
        'available_seats',
        'status',
        'notes'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function departureCity()
    {
        return $this->belongsTo(City::class, 'departure_city_id');
    }

    public function arrivalCity()
    {
        return $this->belongsTo(City::class, 'arrival_city_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('departure_time', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('departure_time', '<', now());
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->has('departure_city_id')) {
            $query->where('departure_city_id', $request->departure_city_id);
        }

        if ($request->has('arrival_city_id')) {
            $query->where('arrival_city_id', $request->arrival_city_id);
        }

        if ($request->has('departure_date')) {
            $query->whereDate('departure_time', $request->departure_date);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        return $query;
    }
}