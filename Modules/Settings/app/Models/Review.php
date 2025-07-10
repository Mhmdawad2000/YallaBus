<?php

namespace Modules\Settings\Models;

use App\Models\BaseModel;
use Modules\Trip\Models\Trip;
use Modules\User\Models\User;
class Review extends BaseModel
{

    protected $fillable = [
        'user_id',
        'trip_id',
        'rating',
        'comment',
    ];

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trip that was reviewed.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
