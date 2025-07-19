<?php

namespace Modules\Booking\Models;

use App\Models\BaseModel;
use Modules\Trip\Models\Trip;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'booking_reference',
        'total_price',
        'status',
        'cancelled_at',
        'cancellation_reason'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}