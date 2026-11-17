<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlucoseReading extends Model
{
    // المفتاح الأساسي للجدول
    protected $primaryKey = 'glucose_reading_id';

    // الحقول المسموح بحفظها وتعديلها
    protected $fillable = [
        'child_id',
        'reading_type',
        'glucose_level',
        'unit',
        'reading_date',
        'reading_time',
        'meal_status',
        'meal_id',
    ];

    // تحويل أنواع البيانات
    protected $casts = [
        'reading_date' => 'date',
        'glucose_level' => 'decimal:2',
    ];

    // علاقة قراءة السكر بالطفل
    public function child()
    {
        return $this->belongsTo(
            Child::class,
            'child_id',
            'child_id'
        );
    }

    // علاقة قراءة السكر بالوجبة
    public function meal()
    {
        return $this->belongsTo(
            Meal::class,
            'meal_id',
            'meal_id'
        );
    }
}