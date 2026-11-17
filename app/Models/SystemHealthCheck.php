<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemHealthCheck extends Model
{
    protected $table = 'system_health_checks';

    protected $fillable = [
        'category',
        'check_key',
        'status',
        'message',
        'details',
        'checked_at',
    ];

    protected $casts = [
        'details' => 'array',
        'checked_at' => 'datetime',
    ];
}
