<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'child_id',
        'report_type',
        'title',
        'start_date',
        'end_date',
        'summary',
        'report_data',
        'generated_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'report_data' => 'array',
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