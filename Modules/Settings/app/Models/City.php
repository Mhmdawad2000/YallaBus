<?php

namespace Modules\Settings\Models;

use App\Models\BaseModel;
use Modules\User\Models\User;
// use Modules\Settings\Database\Factories\CityFactory;

class City extends BaseModel
{

    protected $fillable = [
        'name',
        'user_count',
        'country_id'
    ];

    protected $casts = [
        'user_count' => 'integer'
    ];

    // Relationship with country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Optional: Relationship with users if you have user-city association
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
