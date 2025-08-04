<?php

namespace Modules\Booking\Models;

use App\Models\BaseModel;
use Modules\Company\Models\Seat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Booking\Database\Factories\BookingSeatFactory;

class BookingSeat extends BaseModel
{

    /**
     * The table associated with the model.
     */
    protected $table = 'booking_seats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'seat_id',
        'price',
    ];

    /**
     * Get the booking associated with this seat.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the seat associated with this booking.
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}