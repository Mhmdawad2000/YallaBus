<?php

namespace Modules\User\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBalanceLog extends BaseModel
{

    protected $table = 'user_balance_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'booking_id',
        'old_balance',
        'new_balance',
        'reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_balance' => 'decimal:2',
        'new_balance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the balance log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking associated with this balance log.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(\Modules\Booking\Models\Booking::class);
    }

    /**
     * Get the amount changed in this transaction.
     */
    public function getAmountAttribute(): float
    {
        return $this->new_balance - $this->old_balance;
    }

    /**
     * Get the transaction type (credit or debit).
     */
    public function getTransactionTypeAttribute(): string
    {
        return $this->amount >= 0 ? 'credit' : 'debit';
    }
}