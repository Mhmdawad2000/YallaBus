<?php

namespace Modules\Company\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Company\Database\Factories\DriverFactory;

class Driver extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'license_number',
        'phone',
        'photo',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */


    /**
     * Get the company that owns the driver.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
