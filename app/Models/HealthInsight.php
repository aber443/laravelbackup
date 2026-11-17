<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthInsight extends Model
{
    protected $primaryKey = 'health_insight_id';

    protected $fillable = [
        'child_id',
        'insight_type',
        'title',
        'summary',
        'recommendations',
        'analysis_data',
        'risk_level',
        'period_start',
        'period_end',
        'generated_at',
    ];

    protected $casts = [
        'recommendations' => 'array',
        'analysis_data' => 'array',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'generated_at' => 'datetime',
    ];

    public function child()
    {
        return $this->belongsTo(
            Child::class,
            'child_id',
            'child_id'
        );
    }
}