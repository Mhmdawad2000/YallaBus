<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\ComplaintFactory;

class Complaint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): ComplaintFactory
    // {
    //     // return ComplaintFactory::new();
    // }
}
