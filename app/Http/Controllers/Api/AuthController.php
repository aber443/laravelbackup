<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // توحيد رقم الهاتف بصيغة 967XXXXXXXXX
    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);

        if (str_starts_with($phone, '967')) {
            return $phone;
        }

        return '967' . $phone;
    }

    // التحقق من الأسماء مثل Flutter
    private function nameRules(): array
    {
        return [
            'required',
            'string',
            'min:2',
            'max:20',
            'regex:/^[A-Za-z\x{0621}-\x{064A} ]+$/u',
            'not_regex:/ {2}/',
            'not_regex:/(.)\1{3,}/u',
        ];
    }

    // ===================================
    // تسجيل حساب ولي أمر جديد
    // ===================================
    public function register(Request $request)
    {
        // التحقق من رقم الهاتف
        $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^(967)?[0-9]{9}$/',
            ],
        ]);

        // توحيد الرقم قبل فحص التكرار
        $request->merge([
            'phone' => $this->normalizePhone(
                $request->input('phone')
            ),
        ]);

        $data = $request->validate([
            'first_name' => $this->nameRules(),

            'middle_name' => $this->nameRules(),

            'last_name' => $this->nameRules(),

            'phone' => [
                'required',
                'string',
                'regex:/^967[0-9]{9}$/',
                Rule::unique('users', 'phone'),
            ],

            // ===================================
            // مدينة إقامة ولي الأمر (إجباري)
            // ===================================
            'city' => [
                'required',
                'string',
                Rule::in([
                    'صنعاء',
                    'صعدة',
                    'تعز',
                    'إب',
                    'الحديدة',
                    'ذمار',
                    'عدن',
                ]),
            ],

            'password' => [
                'required',
                'string',
                'regex:/^[0-9]{6}$/',
            ],
        ], [
            'first_name.required' => 'الاسم الأول مطلوب.',
            'middle_name.required' => 'الاسم الثاني مطلوب.',
            'last_name.required' => 'اللقب مطلوب.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.regex' => 'رقم الهاتف اليمني غير صحيح.',
            'phone.unique' => 'رقم الهاتف مسجل بالفعل.',

            // رسائل التحقق من المدينة
            'city.required' => 'يرجى اختيار مدينة إقامتك.',
            'city.string' => 'مدينة الإقامة غير صحيحة.',
            'city.in' => 'يرجى اختيار مدينة من القائمة المتاحة.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.regex' => 'كلمة المرور يجب أن تكون 6 أرقام.',
        ]);

        // دمج الأسماء الثلاثة في حقل name
        $fullName = implode(' ', [
            trim($data['first_name']),
            trim($data['middle_name']),
            trim($data['last_name']),
        ]);

        // إنشاء حساب ولي الأمر
        $user = User::create([
            'name' => $fullName,
            'phone' => $data['phone'],

            // حفظ مدينة إقامة ولي الأمر
            'city' => $data['city'],

            'email' => null,
            'password' => Hash::make($data['password']),
            'role' => 'parent',
            'is_active' => true,
        ]);

        // إنشاء Sanctum Token
        $token = $user->createToken(
            'tinyguide-mobile'
        )->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح.',
            'token' => $token,
            'token_type' => 'Bearer',

            'user' => [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'phone' => $user->phone,

                // إرجاع المدينة إلى Flutter
                'city' => $user->city,

                'role' => $user->role,
            ],
        ], 201);
    }

    // ===================================
    // تسجيل دخول ولي الأمر
    // ===================================
    public function login(Request $request)
    {
        // التحقق من المدخلات
        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^(967)?[0-9]{9}$/',
            ],

            'password' => [
                'required',
                'string',
                'regex:/^[0-9]{6}$/',
            ],
        ], [
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.regex' => 'رقم الهاتف اليمني غير صحيح.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.regex' => 'كلمة المرور يجب أن تكون 6 أرقام.',
        ]);

        // توحيد رقم الهاتف
        $phone = $this->normalizePhone(
            $data['phone']
        );

        // البحث عن ولي الأمر
        $user = User::where('phone', $phone)
            ->where('role', 'parent')
            ->first();

        // التحقق من كلمة المرور
        if (
            !$user ||
            !Hash::check(
                $data['password'],
                $user->password
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'رقم الهاتف أو كلمة المرور غير صحيحة.',
            ], 401);
        }

        // التحقق من حالة الحساب
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'تم تعطيل حسابك. يرجى التواصل مع الإدارة.',
            ], 403);
        }

        // إنشاء Sanctum Token
        $token = $user->createToken(
            'tinyguide-mobile'
        )->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح.',
            'token' => $token,
            'token_type' => 'Bearer',

            'user' => [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
        ], 200);
    }

    // ===================================
    // تعديل بيانات ولي الأمر
    // ===================================
    public function updateProfile(Request $request)
    {
        // ولي الأمر المسجل دخوله باستخدام Sanctum
        $user = $request->user();

        // التحقق من رقم الهاتف قبل توحيده
        $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^(967)?[0-9]{9}$/',
            ],
        ], [
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.regex' => 'رقم الهاتف اليمني غير صحيح.',
        ]);

        // توحيد رقم الهاتف إلى 967XXXXXXXXX
        $request->merge([
            'phone' => $this->normalizePhone(
                $request->input('phone')
            ),
        ]);

        // التحقق من البيانات الجديدة
        $data = $request->validate([
            'first_name' => $this->nameRules(),

            'middle_name' => $this->nameRules(),

            'last_name' => $this->nameRules(),

            'phone' => [
                'required',
                'string',
                'regex:/^967[0-9]{9}$/',

                // منع تكرار الرقم مع حساب آخر
                // مع السماح للمستخدم بالاحتفاظ برقمه الحالي
                Rule::unique('users', 'phone')
                    ->ignore($user->user_id, 'user_id'),
            ],
        ], [
            'first_name.required' => 'الاسم الأول مطلوب.',
            'middle_name.required' => 'الاسم الثاني مطلوب.',
            'last_name.required' => 'اللقب مطلوب.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.regex' => 'رقم الهاتف اليمني غير صحيح.',
            'phone.unique' => 'رقم الهاتف مسجل بالفعل.',
        ]);

        // دمج الأسماء الثلاثة
        $fullName = implode(' ', [
            trim($data['first_name']),
            trim($data['middle_name']),
            trim($data['last_name']),
        ]);

        // تحديث بيانات الحساب الحالي فقط
        $user->name = $fullName;
        $user->phone = $data['phone'];

        // حفظ التعديلات في MySQL
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات ولي الأمر بنجاح.',
            'user' => [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
        ], 200);
    }

    // ===================================
    // تغيير كلمة مرور ولي الأمر
    // ===================================
    public function changePassword(Request $request)
    {
        // ولي الأمر المسجل دخوله باستخدام Sanctum
        $user = $request->user();

        // التحقق من البيانات المدخلة
        $data = $request->validate([
            'current_password' => [
                'required',
                'string',
                'regex:/^[0-9]{6}$/',
            ],

            'new_password' => [
                'required',
                'string',
                'regex:/^[0-9]{6}$/',
                'confirmed',
            ],
        ], [
            'current_password.required' =>
                'كلمة المرور الحالية مطلوبة.',

            'current_password.regex' =>
                'كلمة المرور الحالية يجب أن تكون 6 أرقام.',

            'new_password.required' =>
                'كلمة المرور الجديدة مطلوبة.',

            'new_password.regex' =>
                'كلمة المرور الجديدة يجب أن تكون 6 أرقام.',

            'new_password.confirmed' =>
                'تأكيد كلمة المرور الجديدة غير متطابق.',
        ]);

        // التحقق من صحة كلمة المرور الحالية
        if (!Hash::check(
            $data['current_password'],
            $user->password
        )) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة.',
            ], 422);
        }

        // منع استخدام نفس كلمة المرور القديمة
        if (Hash::check(
            $data['new_password'],
            $user->password
        )) {
            return response()->json([
                'success' => false,
                'message' =>
                    'كلمة المرور الجديدة يجب أن تختلف عن الحالية.',
            ], 422);
        }

        // تشفير كلمة المرور الجديدة وحفظها
        $user->password = Hash::make(
            $data['new_password']
        );

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح.',
        ], 200);
    }
}