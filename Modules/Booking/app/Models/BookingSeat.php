<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Booking\Database\Factories\BookingSeatFactory;

class BookingSeat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): BookingSeatFactory
    // {
    //     // return BookingSeatFactory::new();
    // }
}
