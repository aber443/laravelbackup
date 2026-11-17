<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $primaryKey = 'child_id';

    protected $fillable = [
        'user_id',
        'name',
        'birth_date',
        'gender',
        'diagnosis_date',
        'diabetes_type',
        'notes',
        'image_path',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'diagnosis_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function glucoseReadings()
    {
        return $this->hasMany(GlucoseReading::class, 'child_id', 'child_id');
    }

    public function meals()
    {
        return $this->hasMany(Meal::class, 'child_id', 'child_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'child_id', 'child_id');
    }

    public function medications()
    {
        return $this->hasMany(Medication::class, 'child_id', 'child_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'child_id', 'child_id');
    }

    public function healthInsights()
    {
        return $this->hasMany(HealthInsight::class, 'child_id', 'child_id');
    }
}