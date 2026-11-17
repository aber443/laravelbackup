<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AdminDashboardSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // ==========================================
    // عرض الملف الشخصي
    // ==========================================

    public function profile()
    {
        $user = User::find(session('user_id'));

        if (!$user) {
            return redirect('/login');
        }

        return view('profile', compact('user'));
    }

    // ==========================================
    // عرض صفحة الإعدادات
    // ==========================================

    public function settings(AdminDashboardSettings $dashboardSettings)
    {
        $user = User::find(session('user_id'));

        if (!$user || !$user->isAdmin()) {
            return redirect('/login');
        }

        $alertLabels = AdminDashboardSettings::ALERTS;
        $alertPreferences = $dashboardSettings->preferences($user);
        $adminAlerts = $dashboardSettings->alertsFor($user);

        $lastLogin = $user->admin_last_login_at
            ? Carbon::parse($user->admin_last_login_at)
            : null;

        return view('settings', compact(
            'user',
            'alertLabels',
            'alertPreferences',
            'adminAlerts',
            'lastLogin'
        ));
    }

    // ==========================================
    // حفظ تفضيلات تنبيهات لوحة الإدارة
    // ==========================================

    public function updateNotificationPreferences(
        Request $request,
        AdminDashboardSettings $dashboardSettings
    ) {
        $user = User::find(session('user_id'));

        if (!$user || !$user->isAdmin()) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'alerts' => ['nullable', 'array'],
            'alerts.*' => ['required', 'in:0,1'],
        ]);

        // الاحتفاظ بكل المفاتيح المعتمدة، حتى عند عدم إرسال أحدها.
        $preferences = $dashboardSettings->preferences($user);
        $submittedPreferences = $validated['alerts'] ?? [];

        foreach (AdminDashboardSettings::ALERTS as $key => $label) {
            if (array_key_exists($key, $submittedPreferences)) {
                $preferences[$key] = $submittedPreferences[$key] === '1';
            } else {
                $preferences[$key] = false;
            }
        }

        $dashboardSettings->savePreferences($user, $preferences);

        return back()->with(
            'notification_preferences_saved',
            'تم حفظ تفضيلات التنبيهات بنجاح.'
        );
    }

    // ==========================================
    // تعديل اسم المشرف والبريد الإلكتروني
    // ==========================================

    public function updateProfile(Request $request)
    {
        $user = User::find(session('user_id'));

        if (!$user || !$user->isAdmin()) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'current_password' => [
                'nullable',
                'string',
            ],
        ]);

        $emailChanged = $validated['email'] !== $user->email;

        if ($emailChanged) {
            if (!$request->filled('current_password')) {
                return back()
                    ->withErrors([
                        'current_password' =>
                            'يجب إدخال كلمة المرور الحالية لتغيير البريد الإلكتروني.',
                    ])
                    ->withInput($request->except('current_password'));
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors([
                        'current_password' =>
                            'كلمة المرور الحالية غير صحيحة.',
                    ])
                    ->withInput($request->except('current_password'));
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'تم تحديث بيانات الحساب بنجاح.');
    }

    // ==========================================
    // عرض صفحة تغيير كلمة المرور
    // ==========================================

    public function changePassword()
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        return view('change_password');
    }

    // ==========================================
    // حفظ كلمة المرور الجديدة
    // ==========================================

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'min:6',
                'confirmed',
            ],
        ]);

        $user = User::find(session('user_id'));

        if (!$user) {
            return redirect('/login');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'كلمة المرور الحالية غير صحيحة.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح.');
    }

    // ==========================================
    // صفحة الخصوصية
    // ==========================================

    public function privacy()
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        return view('privacy');
    }

    // ==========================================
    // صفحة حول التطبيق
    // ==========================================

    public function about()
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        return view('about');
    }
}
