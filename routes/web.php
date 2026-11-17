<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\ChecklistController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SystemPerformanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardHomeController;
use App\Http\Controllers\GlucoseReadingController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MedicalExamController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SystemLogController;


// ===================================
// الصفحة الرئيسية
// ===================================

Route::get('/', function () {
    return redirect('/login');
});


// ===================================
// تسجيل الدخول
// ===================================

Route::get(
    '/login',
    [AuthController::class, 'login']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'storeLogin']
)->middleware(
    \App\Http\Middleware\RecordAdminLogin::class
);


// ===================================
// ABEER - Public Care Share Page
// صفحة استقبال مشاركة متابعة الطفل
// لا تحتاج تسجيل دخول
// ===================================

Route::get('/share/{token}', function ($token) {

    return view('care-share', [
        'token' => $token,
    ]);

})->name('care-shares.public');


// ===================================
// المسارات المحمية لمسؤولة النظام
// ===================================

Route::middleware('admin')->group(function () {


    // ===================================
    // الصفحة الرئيسية
    // ===================================

    Route::get(
        '/dashboard',
        [DashboardHomeController::class, 'index']
    );


    // ===================================
    // أداء النظام
    // ===================================

    Route::get(
        '/admin/performance',
        [SystemPerformanceController::class, 'index']
    )->name('admin.performance.index');

    Route::get(
        '/admin/performance/probe',
        [SystemPerformanceController::class, 'probe']
    )->name('admin.performance.probe');


    // ===================================
    // رسائل المستخدمين
    // ===================================

    Route::get(
        '/admin/contact-messages',
        [ContactMessageController::class, 'index']
    )->name('admin.contact-messages.index');

    Route::patch(
        '/admin/contact-messages/{contactMessage}/review',
        [ContactMessageController::class, 'review']
    )->name('admin.contact-messages.review');

    Route::post(
        '/admin/contact-messages/{contactMessage}/reply',
        [ContactMessageController::class, 'reply']
    )->name('admin.contact-messages.reply');


    // ===================================
    // قياس رضا المستخدمين
    // ===================================

    Route::get(
        '/admin/nps',
        [\App\Http\Controllers\Admin\NpsController::class, 'index']
    )->name('admin.nps.index');


    // ===================================
    // قوائم التحقق
    // ===================================

    Route::prefix('admin/checklists')
        ->name('admin.checklists.')
        ->controller(ChecklistController::class)
        ->group(function () {

            Route::get('/', 'index')->name('index');

            Route::post('/', 'store')->name('store');

            Route::get('/{checklist}', 'show')
                ->whereNumber('checklist')
                ->name('show');

            Route::patch('/{checklist}', 'update')
                ->whereNumber('checklist')
                ->name('update');

            Route::delete('/{checklist}', 'destroy')
                ->whereNumber('checklist')
                ->name('destroy');

            Route::post('/{checklist}/items', 'storeItem')
                ->whereNumber('checklist')
                ->name('items.store');

            Route::patch('/{checklist}/items/{item}', 'updateItem')
                ->whereNumber(['checklist', 'item'])
                ->name('items.update');

            Route::patch('/{checklist}/items/{item}/rename', 'renameItem')
                ->whereNumber(['checklist', 'item'])
                ->name('items.rename');

            Route::delete('/{checklist}/items/{item}', 'destroyItem')
                ->whereNumber(['checklist', 'item'])
                ->name('items.destroy');
        });


    // ===================================
    // إدارة المستخدمين
    // ===================================

    Route::get(
        '/users',
        [DashboardController::class, 'users']
    )->name('users.index');

    Route::get(
        '/users/{user}',
        [DashboardController::class, 'showUser']
    )->name('users.show');

    Route::get(
        '/users/{user}/edit',
        [DashboardController::class, 'editUser']
    )->name('users.edit');

    Route::put(
        '/users/{user}',
        [DashboardController::class, 'updateUser']
    )->name('users.update');

    Route::patch(
        '/users/{user}/disable',
        [DashboardController::class, 'disableUser']
    )->name('users.disable');

    Route::patch(
        '/users/{user}/enable',
        [DashboardController::class, 'enableUser']
    )->name('users.enable');


    // ===================================
    // سجلات النظام
    // ===================================

    Route::get(
        '/system-logs',
        [SystemLogController::class, 'index']
    )->name('system-logs.index');

    Route::get(
        '/system-logs/export',
        [SystemLogController::class, 'export']
    )->name('system-logs.export');

    Route::get(
        '/system-logs/{id}',
        [SystemLogController::class, 'show']
    )
        ->whereNumber('id')
        ->name('system-logs.show');


    // ===================================
    // الأطفال
    // ===================================

    Route::get(
        '/children',
        [ChildController::class, 'index']
    )->name('children.index');

    Route::get(
        '/children/{child}',
        [ChildController::class, 'show']
    )->name('children.show');


    // ===================================
    // الإشعارات
    // ===================================

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::get(
        '/notifications/create',
        [NotificationController::class, 'create']
    )->name('notifications.create');

    Route::get(
        '/notifications/recipients/search',
        [NotificationController::class, 'searchRecipients']
    )->name('notifications.recipients.search');

    Route::post(
        '/notifications/send',
        [NotificationController::class, 'send']
    )->name('notifications.send');

    Route::patch(
        '/notifications/read-all',
        [NotificationController::class, 'markAllAsRead']
    )->name('notifications.readAll');

    Route::patch(
        '/notifications/{notificationId}/read',
        [NotificationController::class, 'markAsRead']
    )->name('notifications.read');

    Route::delete(
        '/notifications/{notificationId}',
        [NotificationController::class, 'destroy']
    )->name('notifications.destroy');


    // ===================================
    // السجلات الصحية
    // ===================================

    Route::get('/records', function () {
        return view('records');
    });

    Route::get(
        '/glucose-readings',
        [GlucoseReadingController::class, 'index']
    );

    Route::get(
        '/meals',
        [MealController::class, 'index']
    );

    Route::get(
        '/activities',
        [ActivityController::class, 'index']
    );

    Route::get(
        '/medications',
        [MedicationController::class, 'index']
    );

    Route::get(
        '/medical-exams',
        [MedicalExamController::class, 'index']
    )->name('medical-exams.index');


    // ===================================
    // التقارير
    // ===================================

    Route::get(
        '/reports',
        [ReportController::class, 'index']
    );

    Route::get(
        '/reports/glucose',
        [ReportController::class, 'glucose']
    );

    Route::get(
        '/reports/meals',
        [ReportController::class, 'meals']
    );

    Route::get(
        '/reports/activities',
        [ReportController::class, 'activities']
    );

    Route::get(
        '/reports/growth',
        [ReportController::class, 'growth']
    );

    Route::get(
        '/reports/medications',
        [ReportController::class, 'medications']
    );

    Route::get(
        '/reports/exams',
        [ReportController::class, 'exams']
    );

    Route::get(
        '/reports/appointments',
        [ReportController::class, 'appointments']
    );


    // ===================================
    // الملف الشخصي والإعدادات
    // ===================================

    Route::get(
        '/profile',
        [ProfileController::class, 'profile']
    );

    Route::get(
        '/settings',
        [ProfileController::class, 'settings']
    );

    Route::put(
        '/settings/notification-preferences',
        [ProfileController::class, 'updateNotificationPreferences']
    )->name('settings.notification-preferences.update');

    Route::put(
        '/profile/update',
        [ProfileController::class, 'updateProfile']
    )->name('profile.update');

    Route::get(
        '/change-password',
        [ProfileController::class, 'changePassword']
    );

    Route::post(
        '/change-password',
        [ProfileController::class, 'updatePassword']
    );

    Route::get(
        '/privacy',
        [ProfileController::class, 'privacy']
    );

    Route::get(
        '/about',
        [ProfileController::class, 'about']
    );


    // ===================================
    // تسجيل الخروج
    // ===================================

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );

});