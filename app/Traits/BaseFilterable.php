<?php
namespace App\Traits;

use App\Services\BaseFilter;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait BaseFilterable
{
    public static function filter(Request $request): Builder
    {
        return (new BaseFilter(static::class, $request))->execute();
    }

}