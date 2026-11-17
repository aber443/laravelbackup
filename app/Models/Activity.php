<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $primaryKey = 'activity_id';

    protected $fillable = [
        'child_id',
        'activity_name',
        'duration',
        'intensity',
        'activity_date',
        'activity_time',
    ];

    protected $casts = [
        'activity_date' => 'date',
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