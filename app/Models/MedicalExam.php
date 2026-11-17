<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalExam extends Model
{
    protected $primaryKey = 'medical_exam_id';

    protected $fillable = [
        'child_id',
        'exam_name',
        'exam_result',
        'measurement_unit',
        'exam_date',
        'attachment_path',
        'notes',
    ];

    protected $casts = [
        'exam_date' => 'date',
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