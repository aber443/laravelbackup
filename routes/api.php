<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\GlucoseReadingController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\ContactMessageController;

use App\Http\Controllers\Api\CareShareController;
use App\Http\Controllers\Api\SharedRecordSubmissionController;

use App\Http\Controllers\Api\MedicationController;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\Api\NotificationController;

use App\Http\Controllers\Api\MealAnalysisController;
use App\Http\Controllers\Api\HealthAnalysisController;


// ===================================
// إنشاء حساب ولي أمر جديد
// ===================================

Route::post(
    '/register',
    [AuthController::class, 'register']
);


// ===================================
// تسجيل دخول ولي أمر
// ===================================

Route::post(
    '/login',
    [AuthController::class, 'login']
);


// ===================================
// فحص استجابة API
// ===================================

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'واجهات API تعمل بنجاح.',
        'timestamp' => now()->toIso8601String(),
    ], 200);
})->name('api.health');


// ===================================
// ABEER - Care Share Public API
// مسارات المستلم بدون تسجيل دخول
// ===================================

// التحقق من رابط المشاركة ورمز الدخول
// وإصدار رمز وصول مؤقت
Route::post(
    '/shared-record-submissions/verify',
    [SharedRecordSubmissionController::class, 'verifyAccess']
)->middleware('throttle:10,1');


// استقبال تسجيل جديد من المدرسة أو الأقارب
// وحفظه مباشرة في سجلات الطفل
Route::post(
    '/shared-record-submissions',
    [SharedRecordSubmissionController::class, 'store']
)->middleware('throttle:10,1');


// ===================================
// المسارات المحمية بتسجيل الدخول
// ===================================

Route::middleware('auth:sanctum')->group(function () {


    // ===================================
    // بيانات ولي الأمر
    // ===================================

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put(
        '/user',
        [AuthController::class, 'updateProfile']
    );

    Route::put(
        '/user/change-password',
        [AuthController::class, 'changePassword']
    );


    // ===================================
    // الأطفال
    // ===================================

    Route::get(
        '/children',
        [ChildController::class, 'index']
    );

    Route::post(
        '/children',
        [ChildController::class, 'store']
    );

    Route::put(
        '/children/{childId}',
        [ChildController::class, 'update']
    );


    // ===================================
    // Activity API
    // ===================================

    // جلب أنشطة الطفل
    Route::get(
        '/children/{childId}/activities',
        [ActivityController::class, 'index']
    );

    // إضافة نشاط جديد
    Route::post(
        '/children/{childId}/activities',
        [ActivityController::class, 'store']
    );

    // حذف نشاط محدد للطفل
    Route::delete(
        '/children/{childId}/activities/{activityId}',
        [ActivityController::class, 'destroy']
    );


    // ===================================
    // Glucose API
    // ===================================

    Route::get(
        '/glucose-readings',
        [GlucoseReadingController::class, 'index']
    );

    Route::post(
        '/glucose-readings',
        [GlucoseReadingController::class, 'store']
    );

    Route::delete(
        '/glucose-readings/{readingId}',
        [GlucoseReadingController::class, 'destroy']
    );


    // ===================================
    // Meal API
    // ===================================

    Route::get(
        '/meals',
        [MealController::class, 'index']
    );

    Route::post(
        '/meals',
        [MealController::class, 'store']
    );

    Route::delete(
        '/meals/{mealId}',
        [MealController::class, 'destroy']
    );


    // ===================================
    // Medication API
    // ===================================

    Route::get(
        '/children/{childId}/medications',
        [MedicationController::class, 'index']
    );

    Route::post(
        '/children/{childId}/medications',
        [MedicationController::class, 'store']
    );

    Route::put(
        '/children/{childId}/medications/{medicationId}',
        [MedicationController::class, 'update']
    );

    Route::delete(
        '/children/{childId}/medications/{medicationId}',
        [MedicationController::class, 'destroy']
    );


    // ===================================
    // Reminder API
    // ===================================

    Route::get(
        '/reminders',
        [ReminderController::class, 'index']
    );

    Route::post(
        '/reminders',
        [ReminderController::class, 'store']
    );

    Route::delete(
        '/reminders/{reminderId}',
        [ReminderController::class, 'destroy']
    );


    // ===================================
    // Notification API
    // ===================================

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    );

    Route::patch(
        '/notifications/read-all',
        [NotificationController::class, 'markAllAsRead']
    );

    Route::patch(
        '/notifications/{notificationId}/read',
        [NotificationController::class, 'markAsRead']
    );

    Route::delete(
        '/notifications/{notificationId}',
        [NotificationController::class, 'destroy']
    );


    // ===================================
    // Notification Settings API
    // ===================================

    Route::get(
        '/notification-settings',
        [NotificationController::class, 'getSettings']
    );

    Route::put(
        '/notification-settings',
        [NotificationController::class, 'updateSettings']
    );


    // ===================================
    // Meal Analysis API
    // ===================================

    Route::get(
        '/meal-analysis',
        [MealAnalysisController::class, 'show']
    );


    // ===================================
    // Health Analysis API
    // ===================================

    Route::get(
        '/health-analysis',
        [HealthAnalysisController::class, 'show']
    );


    // ===================================
    // Reports API
    // ===================================

    Route::post(
        '/reports/generate',
        [ReportController::class, 'generate']
    );

    Route::post(
        '/reports',
        [ReportController::class, 'store']
    );

    Route::get(
        '/reports',
        [ReportController::class, 'index']
    );


    // ===================================
    // Device Tokens API
    // ===================================

    Route::post(
        '/device-tokens',
        [DeviceTokenController::class, 'store']
    );


    // ===================================
    // Contact Us API
    // ===================================

    // جلب رسائل ولي الأمر وردود الإدارة
    Route::get(
        '/contact-messages',
        [ContactMessageController::class, 'index']
    );

    // إرسال استفسار أو اقتراح أو بلاغ أو تقييم
    Route::post(
        '/contact-messages',
        [ContactMessageController::class, 'store']
    );


    // ===================================
    // ABEER - Care Share API
    // إدارة المشاركات بواسطة ولي الأمر
    // ===================================

    // إنشاء مشاركة متابعة جديدة
    Route::post(
        '/care-shares',
        [CareShareController::class, 'store']
    );

    // عرض مشاركات ولي الأمر
    Route::get(
        '/care-shares',
        [CareShareController::class, 'index']
    );

    // إلغاء مشاركة
    Route::delete(
        '/care-shares/{shareId}',
        [CareShareController::class, 'destroy']
    );


    // ===================================
    // ABEER - Shared Record Submissions
    // عرض التسجيلات الواردة لولي الأمر
    // ===================================

    // عرض جميع التسجيلات المرتبطة بمشاركة معينة
    Route::get(
        '/care-shares/{shareId}/submissions',
        [SharedRecordSubmissionController::class, 'index']
    );


    // ===================================
    // END - Care Share API
    // ===================================

});