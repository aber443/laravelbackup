<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $primaryKey = 'system_log_id';

    protected $fillable = [
        'user_id',
        'event_type',
        'severity',
        'action',
        'description',
        'source',
        'method',
        'url',
        'status_code',
        'duration_ms',
        'ip_address',
        'user_agent',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'duration_ms' => 'integer',
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    // المستخدم المرتبط بالسجل
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }
}