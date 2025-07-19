<?php

namespace Modules\Company\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'company_id',
        'plate_number',
        'model',
        'capacity',
        'type',
        'amenities',
    ];

    protected $casts = [
        'amenities' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }


    protected static function booted()
    {
        static::created(function ($bus) {
            $bus->createSeatsAutomatically();
        });
    }

    protected function createSeatsAutomatically()
    {
        $seats = [];
        $now = now();

        for ($i = 1; $i <= $this->capacity; $i++) {
            $seats[] = [
                'bus_id' => $this->id,
                'seat_number' => $i,
                'class' => $this->type,
                'is_available' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Seat::insert($seats);
    }
}