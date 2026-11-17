<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // عرض صفحة تسجيل دخول الأدمن
    public function login()
    {
        if (session('user_id')) {
            $user = User::find(session('user_id'));

            if ($user && $user->isAdmin() && $user->is_active) {
                return redirect('/dashboard');
            }
        }

        return view('login');
    }

    // تسجيل دخول الأدمن
    public function storeLogin(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'أدخلي بريدًا إلكترونيًا صحيحًا.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        $user = User::where('email', $data['email'])->first();

        // التحقق من بيانات الدخول
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
                ])
                ->withInput($request->only('email'));
        }

        // السماح للأدمن فقط
        if (!$user->isAdmin()) {
            return back()
                ->withErrors([
                    'email' => 'هذا الحساب غير مصرح له بالدخول إلى لوحة الإدارة.',
                ])
                ->withInput($request->only('email'));
        }

        // التحقق من حالة الحساب
        if (!$user->is_active) {
            return back()
                ->withErrors([
                    'email' => 'هذا الحساب معطّل.',
                ])
                ->withInput($request->only('email'));
        }

        // إنشاء جلسة تسجيل الدخول
        $request->session()->regenerate();

        $request->session()->put('user_id', $user->user_id);

        return redirect('/dashboard');
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        $request->session()->forget('user_id');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
