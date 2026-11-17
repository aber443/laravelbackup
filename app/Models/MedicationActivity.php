<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationActivity extends Model
{
    protected $fillable = [
        'user_id',
        'medication_id',
        'action',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    // المستخدم الذي نفذ العملية
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الدواء المرتبط بالعملية
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}