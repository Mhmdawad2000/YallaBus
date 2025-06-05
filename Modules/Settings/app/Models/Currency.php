<?php

namespace Modules\Settings\App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\CurrencyFactory;

class Currency extends BaseModel
{

    protected $fillable = [
        'currency',
        'symbol',
        'display',
        'is_default',
        'exchange_rate'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'exchange_rate' => 'double'
    ];

    // Relationship with countries
    public function countries()
    {
        return $this->hasMany(Country::class);
    }
}
