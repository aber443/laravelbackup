<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    // ===== START: ABEER - Medication Model =====

    protected $primaryKey = 'medication_id';

    protected $fillable = [
        'child_id',
        'medication_name',
        'insulin_type',
        'dosage',
        'dosage_unit',
        'frequency',
        'reminder_times',
        'start_date',
        'reminder_enabled',
    ];

    protected $casts = [
        'dosage' => 'decimal:2',
        'reminder_times' => 'array',
        'start_date' => 'date',
        'reminder_enabled' => 'boolean',
    ];

    public function child()
    {
        return $this->belongsTo(
            Child::class,
            'child_id',
            'child_id'
        );
    }

    // ===== END: ABEER - Medication Model =====
}