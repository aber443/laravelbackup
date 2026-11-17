<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $primaryKey = 'meal_id';

    // =====================================
    // الحقول المسموح بحفظها
    // =====================================
    protected $fillable = [
        'child_id',
        'meal_type',
        'ingredients',
        'meal_date',
        'meal_time',
        'notes',
    ];

    // =====================================
    // تحويل أنواع البيانات
    // =====================================
    protected $casts = [
        'ingredients' => 'array',
        'meal_date' => 'date',
    ];

    // =====================================
    // علاقة الوجبة بالطفل
    // =====================================
    public function child()
    {
        return $this->belongsTo(
            Child::class,
            'child_id',
            'child_id'
        );
    }
}