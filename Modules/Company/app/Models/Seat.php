<?php

namespace Modules\Company\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'bus_id',
        'seat_number',
        'class',
        'is_available'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    protected static function booted()
    {
        static::creating(function ($seat) {
            $bus = Bus::find($seat->bus_id);

            if (!$bus) {
                throw new \Exception('الحافلة غير موجودة.');
            }

            $seatsCount = $bus->seats()->count();

            if ($seatsCount >= $bus->capacity) {
                throw new \Exception('لا يمكن إضافة مقعد جديد، لقد تم الوصول إلى السعة القصوى.');
            }
        });
    }

}