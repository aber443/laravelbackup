<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\GlucoseReading;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GlucoseReadingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | حفظ قراءة السكر
    | POST /api/glucose-readings
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بتسجيل قراءة السكر.',
            ], 403);
        }

        $childValidator = Validator::make(
            $request->all(),
            [
                'child_id' => ['required', 'integer'],
            ]
        );

        if ($childValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تحديد الطفل أولًا.',
                'errors' => $childValidator->errors(),
            ], 422);
        }

        $child = Child::where(
            'user_id',
            $user->user_id
        )
            ->where(
                'child_id',
                $request->input('child_id')
            )
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الطفل غير موجود أو لا تملكين صلاحية تسجيل قراءة له.',
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'reading_type' => [
                    'required',
                    Rule::in([
                        'قبل الوجبة',
                        'بعد الوجبة',
                        'صائم',
                    ]),
                ],

                'glucose_level' => [
                    'required',
                    'numeric',
                    'gt:0',
                    'max:999999.99',
                ],

                'unit' => [
                    'required',
                    Rule::in(['mg/dL']),
                ],

                'reading_date' => [
                    'required',
                    'date_format:Y-m-d',
                ],

                'reading_time' => [
                    'required',
                    'date_format:H:i:s',
                ],

                'meal_status' => [
                    Rule::requiredIf(
                        fn () =>
                            $request->input('reading_type') ===
                            'بعد الوجبة'
                    ),
                    'nullable',
                    Rule::in([
                        'الوجبة مسجلة في التطبيق',
                        'الوجبة غير مسجلة في التطبيق',
                    ]),
                ],

                'meal_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('meals', 'meal_id')
                        ->where(
                            'child_id',
                            $child->child_id
                        ),
                ],
            ],
            [
                'reading_type.required' =>
                    'نوع القراءة مطلوب.',

                'glucose_level.required' =>
                    'مستوى السكر مطلوب.',

                'glucose_level.numeric' =>
                    'يجب إدخال مستوى سكر صحيح.',

                'glucose_level.gt' =>
                    'يجب أن يكون مستوى السكر أكبر من صفر.',

                'unit.required' =>
                    'وحدة القياس مطلوبة.',

                'reading_date.required' =>
                    'تاريخ القراءة مطلوب.',

                'reading_time.required' =>
                    'وقت القراءة مطلوب.',

                'meal_status.required' =>
                    'يجب تحديد حالة الوجبة.',

                'meal_id.exists' =>
                    'الوجبة غير موجودة أو لا تتبع الطفل المحدد.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' =>
                    'يرجى التحقق من بيانات قراءة السكر.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $readingDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $data['reading_date'] . ' ' .
                $data['reading_time'],
            'Asia/Aden'
        );

        if (
            $readingDateTime->startOfDay()->lt(
                Carbon::parse($child->birth_date)->startOfDay()
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لا يمكن تسجيل قراءة قبل تاريخ ميلاد الطفل.',
            ], 422);
        }

        $readingDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $data['reading_date'] . ' ' .
                $data['reading_time'],
            'Asia/Aden'
        );

        if ($readingDateTime->isFuture()) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لا يمكن تسجيل قراءة بتاريخ أو وقت مستقبلي.',
            ], 422);
        }

        if (
            $data['reading_type'] === 'بعد الوجبة' &&
            ($data['meal_status'] ?? null) ===
                'الوجبة غير مسجلة في التطبيق' &&
            empty($data['meal_id'])
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'يجب حفظ الوجبة أولًا قبل تسجيل قراءة السكر.',
            ], 422);
        }

        $reading = GlucoseReading::create([
            'child_id' => $child->child_id,
            'reading_type' => $data['reading_type'],
            'glucose_level' => $data['glucose_level'],
            'unit' => $data['unit'],
            'reading_date' => $data['reading_date'],
            'reading_time' => $data['reading_time'],

            'meal_status' =>
                $data['reading_type'] === 'بعد الوجبة'
                    ? ($data['meal_status'] ?? null)
                    : null,

            'meal_id' =>
                $data['reading_type'] === 'بعد الوجبة'
                    ? ($data['meal_id'] ?? null)
                    : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ قراءة السكر بنجاح.',
            'glucose_reading' => $reading,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | عرض قراءات السكر للطفل
    | GET /api/glucose-readings?child_id=1
    |--------------------------------------------------------------------------
    */

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
                'message' => 'غير مسموح بعرض قراءات السكر.',
            ], 403);
        }

        $validated = $request->validate([
            'child_id' => ['required', 'integer'],
        ]);

        $child = Child::where(
            'user_id',
            $user->user_id
        )
            ->where(
                'child_id',
                $validated['child_id']
            )
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الطفل غير موجود أو لا تملكين صلاحية عرض قراءاته.',
            ], 404);
        }

        $readings = GlucoseReading::where(
            'child_id',
            $child->child_id
        )
            ->orderByDesc('reading_date')
            ->orderByDesc('reading_time')
            ->orderByDesc('glucose_reading_id')
            ->get();

        return response()->json([
            'success' => true,
            'glucose_readings' => $readings,
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | حذف قراءة السكر
    | DELETE /api/glucose-readings/{readingId}
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request, int $readingId)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بحذف قراءة السكر.',
            ], 403);
        }

        $reading = GlucoseReading::where(
            'glucose_reading_id',
            $readingId
        )
            ->whereHas('child', function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->first();

        if (!$reading) {
            return response()->json([
                'success' => false,
                'message' =>
                    'قراءة السكر غير موجودة أو لا تملكين صلاحية حذفها.',
            ], 404);
        }

        $reading->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف قراءة السكر بنجاح.',
        ], 200);
    }
}