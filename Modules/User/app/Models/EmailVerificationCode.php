<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\User\Database\Factories\EmailVerificationCodeFactory;

class EmailVerificationCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['email', 'code', 'expires_at'];

    // protected static function newFactory(): EmailVerificationCodeFactory
    // {
    //     // return EmailVerificationCodeFactory::new();
    // }
}
