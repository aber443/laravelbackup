<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $primaryKey = 'reminder_id';

    protected $fillable = [
        'user_id',
        'child_id',
        'title',
        'type',
        'medication_id',
        'appointment_id',
        'remind_at',
        'is_active',
        'last_sent_at',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }

    public function child()
    {
        return $this->belongsTo(
            Child::class,
            'child_id',
            'child_id'
        );
    }

    public function medication()
    {
        return $this->belongsTo(
            Medication::class,
            'medication_id',
            'medication_id'
        );
    }

    public function appointment()
    {
        return $this->belongsTo(
            Appointment::class,
            'appointment_id',
            'appointment_id'
        );
    }
}