<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Meal;
use App\Models\GlucoseReading;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MealController extends Controller
{
    // =====================================
    // حفظ وجبة جديدة في MySQL
    // POST /api/meals
    // =====================================

    public function store(Request $request)
    {
        // =====================================
        // 1. التحقق من ولي الأمر
        // =====================================

        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بتسجيل الوجبات.',
            ], 403);
        }

        // =====================================
        // 2. التحقق من رقم الطفل
        // =====================================

        $childValidator = Validator::make(
            $request->all(),
            [
                'child_id' => [
                    'required',
                    'integer',
                ],
            ],
            [
                'child_id.required' =>
                    'يرجى اختيار الطفل أولًا.',
                'child_id.integer' =>
                    'رقم الطفل غير صحيح.',
            ]
        );

        if ($childValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تحديد الطفل أولًا.',
                'errors' => $childValidator->errors(),
            ], 422);
        }

        // التأكد أن الطفل يتبع ولي الأمر المسجل
        $child = Child::where(
            'child_id',
            $request->input('child_id')
        )
            ->where('user_id', $user->user_id)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الطفل غير موجود أو لا تملكين صلاحية تسجيل وجبة له.',
            ], 404);
        }

        // =====================================
        // 3. التحقق من بيانات الوجبة
        // =====================================

        $validator = Validator::make(
            $request->all(),
            [
                'meal_type' => [
                    'required',
                    Rule::in([
                        'الإفطار',
                        'الغداء',
                        'العشاء',
                        'وجبة خفيفة',
                    ]),
                ],

                // الأطعمة المختارة ومجموعاتها الغذائية
                'ingredients' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'ingredients.*' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'ingredients.*.*' => [
                    'required',
                    'string',
                    'max:50',
                ],

                'meal_date' => [
                    'required',
                    'date_format:Y-m-d',
                ],

                'meal_time' => [
                    'required',
                    'date_format:H:i:s',
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:200',
                ],
            ],
            [
                'meal_type.required' =>
                    'يرجى اختيار نوع الوجبة.',

                'meal_type.in' =>
                    'نوع الوجبة غير صحيح.',

                'ingredients.required' =>
                    'يرجى اختيار طعام واحد على الأقل.',

                'ingredients.array' =>
                    'بيانات الأطعمة غير صحيحة.',

                'ingredients.min' =>
                    'يرجى اختيار طعام واحد على الأقل.',

                'ingredients.*.required' =>
                    'يجب أن تحتوي المجموعة الغذائية على أطعمة.',

                'ingredients.*.array' =>
                    'بيانات المجموعة الغذائية غير صحيحة.',

                'ingredients.*.min' =>
                    'يجب اختيار طعام واحد على الأقل في المجموعة.',

                'ingredients.*.*.required' =>
                    'اسم الطعام مطلوب.',

                'ingredients.*.*.string' =>
                    'اسم الطعام غير صحيح.',

                'ingredients.*.*.max' =>
                    'اسم الطعام طويل جدًا.',

                'meal_date.required' =>
                    'يرجى اختيار تاريخ الوجبة.',

                'meal_date.date_format' =>
                    'صيغة تاريخ الوجبة غير صحيحة.',

                'meal_time.required' =>
                    'يرجى اختيار وقت الوجبة.',

                'meal_time.date_format' =>
                    'صيغة وقت الوجبة غير صحيحة.',

                'notes.max' =>
                    'يجب ألا تتجاوز الملاحظات 200 حرف.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من بيانات الوجبة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // =====================================
        // 4. التحقق من التاريخ والوقت
        // =====================================

        $mealDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $data['meal_date'] . ' ' . $data['meal_time'],
            'Asia/Aden'
        );

        // منع تسجيل وجبة قبل ميلاد الطفل
        $birthDate = Carbon::parse(
            $child->birth_date
        )->toDateString();

        if ($mealDateTime->toDateString() < $birthDate) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لا يمكن تسجيل وجبة قبل تاريخ ميلاد الطفل.',
            ], 422);
        }

        // منع تسجيل وجبة في المستقبل
        if ($mealDateTime->isAfter(Carbon::now('Asia/Aden'))) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لا يمكن تسجيل وجبة بتاريخ أو وقت مستقبلي.',
            ], 422);
        }

        // =====================================
        // 5. حفظ الوجبة في MySQL
        // =====================================

        $meal = Meal::create([
            'child_id' => $child->child_id,

            'meal_type' => $data['meal_type'],

            'ingredients' => $data['ingredients'],

            'meal_date' => $data['meal_date'],

            'meal_time' => $data['meal_time'],

            'notes' => $data['notes'] ?? null,
        ]);

        // =====================================
        // 6. إرجاع نتيجة الحفظ إلى Flutter
        // =====================================

        return response()->json([
            'success' => true,

            'message' => 'تم حفظ الوجبة بنجاح.',

            // رقم الوجبة الحقيقي من MySQL
            'meal_id' => $meal->meal_id,

            // بيانات الوجبة المحفوظة
            'meal' => $meal,
        ], 201);
    }

    // =====================================
    // عرض وجبات الطفل من MySQL
    // GET /api/meals?child_id=1
    // =====================================

    public function index(Request $request)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بعرض الوجبات.',
            ], 403);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'child_id' => [
                    'required',
                    'integer',
                ],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تحديد الطفل أولًا.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $child = Child::where(
            'child_id',
            $request->input('child_id')
        )
            ->where('user_id', $user->user_id)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الطفل غير موجود أو لا تملكين صلاحية عرض وجباته.',
            ], 404);
        }

        $meals = Meal::where(
            'child_id',
            $child->child_id
        )
            ->orderByDesc('meal_date')
            ->orderByDesc('meal_time')
            ->orderByDesc('meal_id')
            ->get();

        return response()->json([
            'success' => true,
            'meals' => $meals,
        ], 200);
    }

    // =====================================
    // حذف وجبة من MySQL
    // DELETE /api/meals/{mealId}
    // =====================================

    public function destroy(Request $request, int $mealId)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بحذف الوجبة.',
            ], 403);
        }

        // التأكد أن الوجبة تتبع طفل ولي الأمر
        $meal = Meal::where(
            'meal_id',
            $mealId
        )
            ->whereHas('child', function ($query) use ($user) {
                $query->where(
                    'user_id',
                    $user->user_id
                );
            })
            ->first();

        if (!$meal) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الوجبة غير موجودة أو لا تملكين صلاحية حذفها.',
            ], 404);
        }

        // منع حذف الوجبة المرتبطة بقراءة سكر
        $hasGlucoseReadings = GlucoseReading::where(
            'meal_id',
            $meal->meal_id
        )->exists();

        if ($hasGlucoseReadings) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لا يمكن حذف الوجبة لأنها مرتبطة بقراءة سكر.',
            ], 409);
        }

        // حذف الوجبة غير المرتبطة
        $meal->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الوجبة بنجاح.',
        ], 200);
    }
}