<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\CareShare;
use App\Models\Meal;
use App\Models\GlucoseReading;
use App\Models\Activity;
use App\Models\SharedRecordSubmission;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SharedRecordSubmissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | التحقق من صلاحية المشاركة ومدة صلاحيتها
    |--------------------------------------------------------------------------
    */

    private function checkShareAvailability(
        CareShare $share
    ): ?array {

        $now = Carbon::now('Asia/Aden');

        // التأكد من أن المشاركة لم تُلغَ
        if ($share->revoked_at !== null) {
            return [
                'message' => 'تم إلغاء هذه المشاركة.',
                'status' => 410,
            ];
        }

        // التحقق من وقت بداية المشاركة
        $startsAt = Carbon::parse(
            $share->starts_at,
            'Asia/Aden'
        );

        if ($now->lt($startsAt)) {
            return [
                'message' => 'لم يبدأ وقت المشاركة بعد.',
                'status' => 403,
            ];
        }

        // التحقق من وقت انتهاء المشاركة
        $expiresAt = Carbon::parse(
            $share->expires_at,
            'Asia/Aden'
        );

        if ($now->gte($expiresAt)) {
            return [
                'message' => 'انتهت صلاحية المشاركة.',
                'status' => 410,
            ];
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | البحث عن أقرب وجبة زمنيًا لقراءة السكر
    |--------------------------------------------------------------------------
    |
    | تتم مقارنة وقت القراءة بأوقات جميع وجبات الطفل.
    | يتم اختيار الوجبة ذات أصغر فرق زمني.
    | يمكن أن تكون الوجبة قبل القراءة أو بعدها.
    |
    */

    private function findNearestMeal(
        int $childId,
        Carbon $readingDateTime
    ): ?int {

        // جلب وجبات الطفل
        $meals = Meal::where(
            'child_id',
            $childId
        )
            ->orderByDesc('meal_date')
            ->orderByDesc('meal_time')
            ->get([
                'meal_id',
                'meal_date',
                'meal_time',
            ]);

        $nearestMealId = null;

        $smallestDifference = PHP_INT_MAX;

        foreach ($meals as $meal) {

            // تكوين تاريخ ووقت الوجبة
            $mealDate = $meal->meal_date instanceof Carbon
                ? $meal->meal_date->format('Y-m-d')
                : Carbon::parse($meal->meal_date)->format('Y-m-d');

            $mealDateTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $mealDate . ' ' . $meal->meal_time,
                'Asia/Aden'
            );

            // حساب الفرق بين وقت القراءة ووقت الوجبة
            $difference = abs(
                $readingDateTime->getTimestamp() -
                $mealDateTime->getTimestamp()
            );

            // الاحتفاظ بأقرب وجبة
            if ($difference < $smallestDifference) {

                $smallestDifference = $difference;

                $nearestMealId = (int) $meal->meal_id;
            }
        }

        return $nearestMealId;
    }


    /*
    |--------------------------------------------------------------------------
    | التحقق من رابط المشاركة ورمز الدخول
    | POST /api/shared-record-submissions/verify
    |--------------------------------------------------------------------------
    */

    public function verifyAccess(Request $request)
    {
        // التحقق من البيانات المرسلة
        $validator = Validator::make(
            $request->all(),
            [

                'token' => [
                    'required',
                    'string',
                    'max:128',
                ],

                'access_code' => [
                    'required',
                    'string',
                    'digits:6',
                ],


            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إدخال رابط المشاركة ورمز الدخول بشكل صحيح.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // البحث عن المشاركة باستخدام بصمة الرابط
        $share = CareShare::with('child')
            ->where(
                'token_hash',
                hash('sha256', $data['token'])
            )
            ->first();

        if (!$share) {
            return response()->json([
                'success' => false,
                'message' => 'رابط المشاركة غير صالح.',
            ], 404);
        }

        // التحقق من حالة المشاركة ومدتها
        $availabilityError =
            $this->checkShareAvailability($share);

        if ($availabilityError) {
            return response()->json([
                'success' => false,
                'message' => $availabilityError['message'],
            ], $availabilityError['status']);
        }

        // التحقق من رمز الدخول المشفر
        if (!Hash::check(
            $data['access_code'],
            $share->access_code_hash
        )) {
            return response()->json([
                'success' => false,
                'message' => 'رمز الدخول غير صحيح.',
            ], 422);
        }

        // التأكد من وجود الطفل
        if (!$share->child) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الطفل غير متوفرة.',
            ], 404);
        }

        // =====================================
        // إنشاء رمز وصول مؤقت
        // =====================================

        // إنشاء رمز عشوائي جديد
        $accessToken = Str::random(80);

        // تخزين بصمة رمز الوصول وليس الرمز نفسه
        $cacheKey = 'care_share_access:' .
            hash('sha256', $accessToken);

        // انتهاء رمز الوصول مطابق لانتهاء المشاركة الذي حدده ولي الأمر.
        // لا توجد مدة مستقلة للجلسة ولا خيار (تذكرني).
        $accessTokenExpiresAt = Carbon::parse(
            $share->expires_at,
            'Asia/Aden'
        );

        // حفظ رمز الوصول المؤقت في Cache
        Cache::put(
            $cacheKey,
            $share->share_id,
            $accessTokenExpiresAt
        );

        // =====================================
        // إرجاع بيانات المشاركة
        // =====================================

        return response()->json([

            'success' => true,

            'message' =>
                'تم التحقق من المشاركة بنجاح.',

            'data' => [

                'share_id' =>
                    $share->share_id,

                'child_name' =>
                    $share->child->name,

                // تاريخ ميلاد الطفل لحساب عمره في صفحة الويب
                'birth_date' => $share->child->birth_date
                    ? Carbon::parse(
                        $share->child->birth_date,
                        'Asia/Aden'
                    )->toDateString()
                    : null,

                // الصلاحيات التي منحها ولي الأمر
                'allow_meals' =>
                    $share->allow_meals,

                'allow_glucose' =>
                    $share->allow_glucose,

                'allow_activities' =>
                    $share->allow_activities,

                'expires_at' =>
                    $share->expires_at,

                // يستخدم عند إرسال التسجيلات
                'access_token' =>
                    $accessToken,

                'token_type' => 'Bearer',

                'access_token_expires_at' =>
                    $accessTokenExpiresAt,

            ],

        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | استقبال تسجيل جديد من خلال مشاركة المتابعة
    | POST /api/shared-record-submissions
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        // =====================================
        // 1. التحقق من رمز الوصول المؤقت
        // =====================================

        $accessToken = $request->bearerToken();

        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'يجب التحقق من المشاركة أولًا.',
            ], 401);
        }

        // إنشاء مفتاح Cache باستخدام بصمة الرمز
        $cacheKey = 'care_share_access:' .
            hash('sha256', $accessToken);

        // جلب رقم المشاركة المرتبط برمز الوصول
        $shareId = Cache::get($cacheKey);

        if (!$shareId) {
            return response()->json([
                'success' => false,
                'message' => 'رمز الوصول غير صالح أو انتهت صلاحيته.',
            ], 401);
        }

        // =====================================
        // 2. جلب المشاركة والطفل
        // =====================================

        $share = CareShare::with('child')
            ->where('share_id', $shareId)
            ->first();

        if (!$share || !$share->child) {
            return response()->json([
                'success' => false,
                'message' => 'المشاركة أو بيانات الطفل غير موجودة.',
            ], 404);
        }

        $child = $share->child;

        // إعادة فحص المشاركة قبل كل تسجيل
        $availabilityError =
            $this->checkShareAvailability($share);

        if ($availabilityError) {
            return response()->json([
                'success' => false,
                'message' => $availabilityError['message'],
            ], $availabilityError['status']);
        }

        // =====================================
        // 3. التحقق من نوع التسجيل
        // =====================================

        $validator = Validator::make(
            $request->all(),
            [

                'record_type' => [
                    'required',
                    Rule::in([
                        'meal',
                        'glucose_reading',
                        'activity',
                    ]),
                ],

                'record_data' => [
                    'required',
                    'array',
                ],

            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'نوع التسجيل أو بياناته غير صحيحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $recordType = $data['record_type'];

        $recordData = $data['record_data'];

        // =====================================
        // 4. التحقق من صلاحيات المشاركة
        // =====================================

        $permissionMap = [

            'meal' => 'allow_meals',

            'glucose_reading' => 'allow_glucose',

            'activity' => 'allow_activities',

        ];

        $permissionField = $permissionMap[$recordType];

        // منع تسجيل نوع غير مسموح به
        if (!$share->{$permissionField}) {
            return response()->json([
                'success' => false,
                'message' => 'لا تملك صلاحية تسجيل هذا النوع من البيانات.',
            ], 403);
        }

        // =====================================
        // 5. التحقق من بيانات التسجيل
        // =====================================

        $rules = [];

        $messages = [];

        // -------------------------------------
        // بيانات الوجبة
        // -------------------------------------

        if ($recordType === 'meal') {

            $rules = [

                'meal_type' => [
                    'required',
                    Rule::in([
                        'الإفطار',
                        'الغداء',
                        'العشاء',
                        'وجبة خفيفة',
                    ]),
                ],

                // الأطعمة ومجموعاتها الغذائية
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

            ];

            $messages = [

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

            ];
        }

        // -------------------------------------
        // بيانات قراءة السكر
        // -------------------------------------

        elseif ($recordType === 'glucose_reading') {

            $rules = [

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

                // مطلوب فقط إذا كانت القراءة بعد الوجبة
                'meal_status' => [

                    Rule::requiredIf(
                        fn () =>
                            ($recordData['reading_type'] ?? null)
                            === 'بعد الوجبة'
                    ),

                    'nullable',

                    Rule::in([
                        'الوجبة مسجلة في التطبيق',
                        'الوجبة غير مسجلة في التطبيق',
                    ]),

                ],

            ];

            $messages = [

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

            ];
        }

        // -------------------------------------
        // بيانات النشاط
        // -------------------------------------

        elseif ($recordType === 'activity') {

            $rules = [

                'activity_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                ],

                'duration' => [
                    'required',
                    Rule::in([
                        '15 دقيقة',
                        '30 دقيقة',
                        '45 دقيقة',
                        'ساعة',
                        'أكثر من ساعة',
                    ]),
                ],

                'intensity' => [
                    'required',
                    Rule::in([
                        'خفيف',
                        'متوسط',
                        'شديد',
                    ]),
                ],

                'activity_date' => [
                    'required',
                    'date_format:Y-m-d',
                ],

                'activity_time' => [
                    'required',
                    'date_format:H:i:s',
                ],

            ];

            $messages = [

                'activity_name.required' =>
                    'اسم النشاط مطلوب.',

                'activity_name.min' =>
                    'اسم النشاط قصير جدًا.',

                'activity_name.max' =>
                    'اسم النشاط طويل جدًا.',

                'duration.required' =>
                    'مدة النشاط مطلوبة.',

                'intensity.required' =>
                    'شدة النشاط مطلوبة.',

                'activity_date.required' =>
                    'تاريخ النشاط مطلوب.',

                'activity_time.required' =>
                    'وقت النشاط مطلوب.',

            ];
        }

        // تنفيذ التحقق من بيانات النوع المحدد
        $recordValidator = Validator::make(
            $recordData,
            $rules,
            $messages
        );

        if ($recordValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من بيانات التسجيل.',
                'errors' => $recordValidator->errors(),
            ], 422);
        }

        // استخدام الحقول التي اجتازت التحقق فقط
        $recordData = $recordValidator->validated();

        // =====================================
        // 6. التحقق من تاريخ التسجيل
        // =====================================

        $birthDate = Carbon::parse(
            $child->birth_date,
            'Asia/Aden'
        )->toDateString();

        $now = Carbon::now('Asia/Aden');

        $recordDateTime = null;

        // تكوين تاريخ ووقت الوجبة
        if ($recordType === 'meal') {

            $recordDateTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $recordData['meal_date'] . ' ' .
                $recordData['meal_time'],
                'Asia/Aden'
            );
        }

        // تكوين تاريخ ووقت قراءة السكر
        elseif ($recordType === 'glucose_reading') {

            $recordDateTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $recordData['reading_date'] . ' ' .
                $recordData['reading_time'],
                'Asia/Aden'
            );
        }

        // التحقق من تاريخ الوجبة أو القراءة
        if ($recordDateTime !== null) {

            // منع تسجيل بيانات قبل ميلاد الطفل
            if (
                $recordDateTime->toDateString() < $birthDate
            ) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'لا يمكن تسجيل بيانات قبل تاريخ ميلاد الطفل.',
                ], 422);
            }

            // منع التسجيل بتاريخ أو وقت مستقبلي
            if ($recordDateTime->isAfter($now)) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'لا يمكن تسجيل بيانات بتاريخ أو وقت مستقبلي.',
                ], 422);
            }
        }

        // التحقق من تاريخ النشاط
        if ($recordType === 'activity') {

            $activityDate = $recordData['activity_date'];

            if ($activityDate < $birthDate) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'لا يمكن تسجيل نشاط قبل تاريخ ميلاد الطفل.',
                ], 422);
            }

            if ($activityDate > $now->toDateString()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'لا يمكن تسجيل نشاط بتاريخ مستقبلي.',
                ], 422);
            }
        }

        // =====================================
        // 7. تحديد الوجبة المرتبطة بقراءة السكر
        // =====================================

        $mealId = null;

        if ($recordType === 'glucose_reading') {

            $readingType = $recordData['reading_type'];

            // الصائم لا يرتبط بوجبة
            if ($readingType === 'صائم') {

                $mealId = null;

            }

            // إذا كانت الوجبة بعد القراءة
            // نبحث عن أقرب وجبة زمنيًا
            elseif ($readingType === 'قبل الوجبة') {

                $mealId = $this->findNearestMeal(
                    $child->child_id,
                    $recordDateTime
                );

            }

            // قراءة بعد الوجبة
            elseif ($readingType === 'بعد الوجبة') {

                $mealStatus = $recordData['meal_status'];

                // إذا كانت الوجبة غير مسجلة
                // فلا نربط القراءة بوجبة أخرى
                if (
                    $mealStatus ===
                    'الوجبة غير مسجلة في التطبيق'
                ) {

                    $mealId = null;

                }

                // إذا كانت الوجبة مسجلة
                // نربط القراءة بأقرب وجبة زمنيًا
                elseif (
                    $mealStatus ===
                    'الوجبة مسجلة في التطبيق'
                ) {

                    $mealId = $this->findNearestMeal(
                        $child->child_id,
                        $recordDateTime
                    );

                    // عدم قبول حالة مسجلة دون وجود وجبة
                    if ($mealId === null) {
                        return response()->json([
                            'success' => false,
                            'message' =>
                                'لم يتم العثور على وجبة مسجلة مرتبطة بقراءة السكر.',
                        ], 422);
                    }
                }
            }
        }

        // =====================================
        // 8. حفظ التسجيل وتوثيق مصدره
        // =====================================

        $result = DB::transaction(function () use (
            $recordType,
            $recordData,
            $child,
            $share,
            $now,
            $mealId
        ) {

            $nativeRecord = null;

            // ---------------------------------
            // حفظ وجبة في جدول meals
            // ---------------------------------

            if ($recordType === 'meal') {

                $nativeRecord = Meal::create([

                    'child_id' =>
                        $child->child_id,

                    'meal_type' =>
                        $recordData['meal_type'],

                    'ingredients' =>
                        $recordData['ingredients'],

                    'meal_date' =>
                        $recordData['meal_date'],

                    'meal_time' =>
                        $recordData['meal_time'],

                    'notes' =>
                        $recordData['notes'] ?? null,

                ]);
            }

            // ---------------------------------
            // حفظ قراءة السكر في glucose_readings
            // ---------------------------------

            elseif ($recordType === 'glucose_reading') {

                $nativeRecord = GlucoseReading::create([

                    'child_id' =>
                        $child->child_id,

                    'reading_type' =>
                        $recordData['reading_type'],

                    'glucose_level' =>
                        $recordData['glucose_level'],

                    'unit' =>
                        $recordData['unit'],

                    'reading_date' =>
                        $recordData['reading_date'],

                    'reading_time' =>
                        $recordData['reading_time'],

                    'meal_status' =>
                        $recordData['reading_type'] === 'بعد الوجبة'
                            ? $recordData['meal_status']
                            : null,

                    'meal_id' =>
                        $mealId,

                ]);
            }

            // ---------------------------------
            // حفظ نشاط في جدول activities
            // ---------------------------------

            elseif ($recordType === 'activity') {

                $nativeRecord = Activity::create([

                    'child_id' =>
                        $child->child_id,

                    'activity_name' =>
                        $recordData['activity_name'],

                    'duration' =>
                        $recordData['duration'],

                    'intensity' =>
                        $recordData['intensity'],

                    'activity_date' =>
                        $recordData['activity_date'],

                    'activity_time' =>
                        $recordData['activity_time'],

                ]);
            }

            // =================================
            // توثيق التسجيل في جدول المشاركات
            // =================================

            $auditData = $recordData;

            // حفظ رقم السجل الأصلي
            $auditData['native_record_id'] =
                $nativeRecord->getKey();

            // توثيق مصدر التسجيل
            $auditData['source'] = 'care_share';

            // توثيق الوجبة المرتبطة بقراءة السكر
            if ($recordType === 'glucose_reading') {
                $auditData['meal_id'] = $mealId;
            }

            $submission = SharedRecordSubmission::create([

                'share_id' =>
                    $share->share_id,

                'record_type' =>
                    $recordType,

                'record_data' =>
                    $auditData,

                // اعتماد تلقائي دون موافقة يدوية
                'status' => 'approved',

                // لا يوجد مراجع بشري
                'reviewed_by_user_id' => null,

                // وقت الاعتماد التلقائي
                'reviewed_at' => $now,

                'rejection_reason' => null,

            ]);

            return [

                'native_record' => $nativeRecord,

                'submission' => $submission,

            ];
        });

        // =====================================
        // 9. إرجاع نتيجة التسجيل
        // =====================================

        return response()->json([

            'success' => true,

            'message' =>
                'تم تسجيل البيانات وحفظها بنجاح.',

            'data' => [

                'submission_id' =>
                    $result['submission']->submission_id,

                'record_type' =>
                    $recordType,

                'record_id' =>
                    $result['native_record']->getKey(),

                'status' => 'approved',

                'record' =>
                    $result['native_record'],

            ],

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | عرض التسجيلات المرتبطة بمشاركة معينة
    | GET /api/care-shares/{shareId}/submissions
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        int $shareId
    ) {

        $user = $request->user();

        // التأكد من تسجيل الدخول وصلاحية ولي الأمر
        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بعرض التسجيلات.',
            ], 403);
        }

        // التأكد أن المشاركة تخص ولي الأمر الحالي
        $share = CareShare::where(
            'share_id',
            $shareId
        )
            ->where(
                'user_id',
                $user->user_id
            )
            ->first();

        if (!$share) {
            return response()->json([
                'success' => false,
                'message' =>
                    'المشاركة غير موجودة أو لا تملك صلاحية الوصول إليها.',
            ], 404);
        }

        // جلب جميع التسجيلات المرتبطة بالمشاركة
        $submissions = SharedRecordSubmission::where(
            'share_id',
            $share->share_id
        )
            ->orderByDesc('created_at')
            ->get();

        return response()->json([

            'success' => true,

            'message' =>
                'تم جلب التسجيلات بنجاح.',

            'data' => $submissions,

        ], 200);
    }
}