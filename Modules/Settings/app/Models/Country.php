<?php

namespace Modules\Settings\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\CountryFactory;

class Country extends BaseModel
{

    protected $fillable = [
        'name',
        'dialing_code',
        'currency_id'
    ];

    // Relationship with currency
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    // Relationship with cities
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
