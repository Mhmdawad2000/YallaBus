<?php

namespace Modules\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Modules\Settings\Models\City;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Modules\User\database\factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'code_phone',
        'phone',
        'city_id',
        'role_id',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
    ];
    protected $guard_name = 'web';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'last_login_at',
        'email_verified_at',
        'last_login_ip',
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords($value);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getFullNameAttribute()
    {
        return "{$this->attributes['first_name']} {$this->attributes['last_name']}";
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
