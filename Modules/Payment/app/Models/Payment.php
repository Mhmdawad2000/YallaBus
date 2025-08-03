<?php

namespace Modules\Payment\Models;

use App\Models\BaseModel;
use Modules\Booking\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Payment\Database\Factories\PaymentFactory;

class Payment extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'booking_id',
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'payment_details',
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Define the relationship to the Booking.
     * A payment belongs to a booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}