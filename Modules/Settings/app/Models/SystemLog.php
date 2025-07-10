<?php

namespace Modules\Settings\Models;

use App\Models\BaseModel;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\SystemLogFactory;

class SystemLog extends BaseModel
{
    use HasFactory;
    protected $table = 'system_logs';
    protected $fillable = [
        'model_type',
        'model_id',
        'action',
        'old_data',
        'new_data',
        'user_id',
        'ip_address',
        'user_agent',
        'notification_title',
        'notification_body',
        'comments',
    ];
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo();
    }
}