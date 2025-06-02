<?php

namespace Modules\Trip\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Trip\Database\Factories\TripFactory;

class Trip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): TripFactory
    // {
    //     // return TripFactory::new();
    // }
}
