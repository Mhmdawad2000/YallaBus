<?php

namespace Modules\Settings\Models;

use App\Models\BaseModel;
use Modules\User\Models\User;
use Modules\Booking\Models\Booking;

class Complaint extends BaseModel
{   /**
    * Mass assignable attributes.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'user_id',
        'booking_id',
        'subject',
        'description',
        'status',
        'resolution',
        'resolved_by',
        'resolved_at',
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * المستخدم الذي قدم الشكوى
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحجز المرتبط بالشكوى (قد يكون فارغًا)
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * المسؤول الذي حل الشكوى (قد يكون فارغًا)
     */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
