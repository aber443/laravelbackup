<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Child;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Activities
    |--------------------------------------------------------------------------
    */

    public function index(Request $request, int $childId)
    {
        // التأكد أن الطفل تابع لولي الأمر المسجل دخوله
        $child = Child::where('child_id', $childId)
            ->where(
                'user_id',
                $request->user()->getAuthIdentifier()
            )
            ->first();

        if (!$child) {
            return response()->json([
                'message' => 'الطفل غير موجود أو لا تملك صلاحية الوصول إليه',
            ], 404);
        }

        // جلب أنشطة الطفل المحدد
        $activities = Activity::where(
            'child_id',
            $child->child_id
        )
            ->orderByDesc('activity_date')
            ->orderByDesc('activity_time')
            ->get();

        return response()->json([
            'message' => 'تم جلب الأنشطة بنجاح',
            'data' => $activities,
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Activity
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, int $childId)
    {
        // التأكد من ملكية الطفل
        $child = Child::where('child_id', $childId)
            ->where(
                'user_id',
                $request->user()->getAuthIdentifier()
            )
            ->first();

        if (!$child) {
            return response()->json([
                'message' => 'الطفل غير موجود أو لا تملك صلاحية الوصول إليه',
            ], 404);
        }

        // التحقق من البيانات المرسلة
        $validated = $request->validate([

            'activity_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],

            'duration' => [
                'required',
                'string',
                'in:15 دقيقة,30 دقيقة,45 دقيقة,ساعة,أكثر من ساعة',
            ],

            'intensity' => [
                'required',
                'string',
                'in:خفيف,متوسط,شديد',
            ],

            'activity_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:' . $child->birth_date->format('Y-m-d'),
                'before_or_equal:today',
            ],

            'activity_time' => [
                'required',
                'date_format:H:i:s',
            ],

        ]);

        // حفظ النشاط في قاعدة البيانات
        $activity = Activity::create([

            'child_id' => $child->child_id,

            'activity_name' => $validated['activity_name'],

            'duration' => $validated['duration'],

            'intensity' => $validated['intensity'],

            'activity_date' => $validated['activity_date'],

            'activity_time' => $validated['activity_time'],

        ]);

        return response()->json([
            'message' => 'تم حفظ النشاط بنجاح',
            'data' => $activity,
        ], 201);
    }
}