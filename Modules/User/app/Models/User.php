<?php

namespace Modules\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\BaseFilterable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Settings\Models\City;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\database\factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Company\Models\Company;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;
    use HasRoles;
    use SoftDeletes, BaseFilterable;

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
        'avatar',
        'balance',
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
            'balance' => 'decimal:2',
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
    public function company()
    {
        return $this->hasOne(Company::class, 'admin_id', 'id');
    }

    /**
     * Get the balance logs for the user.
     */
    public function balanceLogs()
    {
        return $this->hasMany(UserBalanceLog::class);
    }

    /**
     * Add amount to user balance and log the transaction.
     */
    public function addBalance(float $amount, string $reason, ?int $bookingId = null): void
    {
        $oldBalance = $this->balance;
        $this->increment('balance', $amount);
        $this->refresh();

        UserBalanceLog::create([
            'user_id' => $this->id,
            'booking_id' => $bookingId,
            'old_balance' => $oldBalance,
            'new_balance' => $this->balance,
            'reason' => $reason,
        ]);
    }

    /**
     * Deduct amount from user balance and log the transaction.
     */
    public function deductBalance(float $amount, string $reason, ?int $bookingId = null): bool
    {
        if ($this->balance < $amount) {
            return false; // Insufficient balance
        }

        $oldBalance = $this->balance;
        $this->decrement('balance', $amount);
        $this->refresh();

        UserBalanceLog::create([
            'user_id' => $this->id,
            'booking_id' => $bookingId,
            'old_balance' => $oldBalance,
            'new_balance' => $this->balance,
            'reason' => $reason,
        ]);

        return true;
    }

    /**
     * Set user balance to a specific amount and log the transaction.
     */
    public function setBalance(float $amount, string $reason, ?int $bookingId = null): void
    {
        $oldBalance = $this->balance;
        $this->update(['balance' => $amount]);

        UserBalanceLog::create([
            'user_id' => $this->id,
            'booking_id' => $bookingId,
            'old_balance' => $oldBalance,
            'new_balance' => $amount,
            'reason' => $reason,
        ]);
    }
}
