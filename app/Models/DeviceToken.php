<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $primaryKey = 'device_token_id';

    protected $fillable = [
        'user_id',
        'fcm_token',
        'platform',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    // علاقة الجهاز بولي الأمر
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }
}