<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CareShare;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CareShareController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | التحقق من صلاحية ولي الأمر
    |--------------------------------------------------------------------------
    */

    private function checkParent(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً.',
            ], 401);
        }

        if (
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بتنفيذ هذا الإجراء.',
            ], 403);
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | إنشاء مشاركة متابعة جديدة
    | POST /api/care-shares
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $permission = $this->checkParent($request);

        if ($permission) {
            return $permission;
        }

        // التحقق من البيانات المرسلة من Flutter
        $validated = $request->validate([

            'child_id' => [
                'required',
                'integer',
            ],

            'starts_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],

            'expires_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],

            'allow_meals' => [
                'required',
                'boolean',
            ],

            'allow_glucose' => [
                'required',
                'boolean',
            ],

            'allow_activities' => [
                'required',
                'boolean',
            ],

        ]);

        // التأكد من أن الطفل تابع لولي الأمر الحالي
        $child = Child::where(
            'child_id',
            $validated['child_id']
        )
            ->where(
                'user_id',
                $request->user()->user_id
            )
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الطفل غير موجود أو لا تملك صلاحية الوصول إليه.',
            ], 404);
        }

        // التأكد من اختيار صلاحية واحدة على الأقل
        if (
            !$validated['allow_meals'] &&
            !$validated['allow_glucose'] &&
            !$validated['allow_activities']
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'يجب اختيار صلاحية واحدة على الأقل.',
            ], 422);
        }

        // =====================================
        // معالجة التوقيت
        // =====================================

        // استقبال الأوقات بتوقيت صنعاء
        $startsAt = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $validated['starts_at'],
            'Asia/Aden'
        );

        $expiresAt = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $validated['expires_at'],
            'Asia/Aden'
        );

        // منع تحديد بداية سابقة
        if ($startsAt->lt(Carbon::now('Asia/Aden'))) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لا يمكن أن تبدأ المشاركة في وقت سابق.',
            ], 422);
        }

        // التأكد من أن الانتهاء بعد البداية
        if (!$expiresAt->gt($startsAt)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'يجب أن يكون وقت انتهاء المشاركة بعد وقت بدايتها.',
            ], 422);
        }

        // تحويل الأوقات إلى UTC قبل تخزينها
        $startsAtUtc = $startsAt->copy()->utc();

        $expiresAtUtc = $expiresAt->copy()->utc();


        // =====================================
        // إنشاء رموز الحماية
        // =====================================

        // إنشاء رمز عشوائي خاص بالرابط
        $plainToken = Str::random(64);

        // تخزين بصمة الرابط فقط
        $tokenHash = hash(
            'sha256',
            $plainToken
        );

        // إنشاء رمز دخول عشوائي مكون من 6 أرقام
        $accessCode = str_pad(
            (string) random_int(0, 999999),
            6,
            '0',
            STR_PAD_LEFT
        );

        // تشفير رمز الدخول قبل تخزينه
        $accessCodeHash = Hash::make(
            $accessCode
        );


        // =====================================
        // حفظ المشاركة في قاعدة البيانات
        // =====================================

        $share = CareShare::create([

            'user_id' =>
                $request->user()->user_id,

            'child_id' =>
                $child->child_id,

            'token_hash' =>
                $tokenHash,

            'access_code_hash' =>
                $accessCodeHash,

            'allow_meals' =>
                $validated['allow_meals'],

            'allow_glucose' =>
                $validated['allow_glucose'],

            'allow_activities' =>
                $validated['allow_activities'],

            // تخزين التواريخ بتوقيت UTC
            'starts_at' =>
                $startsAtUtc,

            'expires_at' =>
                $expiresAtUtc,

        ]);


        // =====================================
        // تجهيز رابط المشاركة
        // =====================================

        $sharePath = url(
            '/share/' . $plainToken
        );

        // إرجاع بيانات المشاركة إلى Flutter
        // الأوقات المعروضة بتوقيت صنعاء
        return response()->json([

            'success' => true,

            'message' =>
                'تم إنشاء مشاركة المتابعة بنجاح.',

            'share' => [

                'share_id' =>
                    $share->share_id,

                'child_id' =>
                    $child->child_id,

                'child_name' =>
                    $child->name,

                'share_path' =>
                    $sharePath,

                // رمز الدخول يظهر عند الإنشاء فقط
                'access_code' =>
                    $accessCode,

                'starts_at' =>
                    $startsAt->format('Y-m-d H:i:s'),

                'expires_at' =>
                    $expiresAt->format('Y-m-d H:i:s'),

                'allow_meals' =>
                    $share->allow_meals,

                'allow_glucose' =>
                    $share->allow_glucose,

                'allow_activities' =>
                    $share->allow_activities,

            ],

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | عرض جميع مشاركات ولي الأمر
    | GET /api/care-shares
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $permission = $this->checkParent($request);

        if ($permission) {
            return $permission;
        }

        // جلب مشاركات ولي الأمر مع بيانات الطفل
        $shares = CareShare::with('child')
            ->where(
                'user_id',
                $request->user()->user_id
            )
            ->orderByDesc('created_at')
            ->get();

        // تحديد حالة كل مشاركة
        $data = $shares->map(function ($share) {

            // جميع المقارنات بالتوقيت العالمي UTC
            $now = Carbon::now('UTC');

            $startsAt = $share->starts_at->copy()->utc();

            $expiresAt = $share->expires_at->copy()->utc();

            // المشاركة الملغاة
            if ($share->revoked_at !== null) {

                $status = 'revoked';

            }

            // المشاركة المنتهية
            elseif ($expiresAt->lte($now)) {

                $status = 'expired';

            }

            // المشاركة التي لم تبدأ بعد
            elseif ($startsAt->gt($now)) {

                $status = 'scheduled';

            }

            // المشاركة النشطة
            else {

                $status = 'active';

            }

            return [

                'share_id' =>
                    $share->share_id,

                'child_id' =>
                    $share->child_id,

                'child_name' =>
                    $share->child?->name,

                'allow_meals' =>
                    $share->allow_meals,

                'allow_glucose' =>
                    $share->allow_glucose,

                'allow_activities' =>
                    $share->allow_activities,

                // إرجاع الأوقات بتوقيت صنعاء
                'starts_at' =>
                    $startsAt->copy()
                        ->setTimezone('Asia/Aden')
                        ->format('Y-m-d H:i:s'),

                'expires_at' =>
                    $expiresAt->copy()
                        ->setTimezone('Asia/Aden')
                        ->format('Y-m-d H:i:s'),

                'revoked_at' =>
                    $share->revoked_at
                        ? $share->revoked_at->copy()
                            ->setTimezone('Asia/Aden')
                            ->format('Y-m-d H:i:s')
                        : null,

                'status' =>
                    $status,

                'created_at' =>
                    $share->created_at,

            ];
        });

        return response()->json([

            'success' => true,

            'message' =>
                'تم جلب المشاركات بنجاح.',

            'data' => $data,

        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | إلغاء مشاركة متابعة
    | DELETE /api/care-shares/{shareId}
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        int $shareId
    ) {
        $permission = $this->checkParent($request);

        if ($permission) {
            return $permission;
        }

        // التأكد من أن المشاركة تخص ولي الأمر الحالي
        $share = CareShare::where(
            'share_id',
            $shareId
        )
            ->where(
                'user_id',
                $request->user()->user_id
            )
            ->first();

        if (!$share) {
            return response()->json([
                'success' => false,
                'message' =>
                    'المشاركة غير موجودة أو لا تملك صلاحية إلغائها.',
            ], 404);
        }

        // التأكد من أنها غير ملغاة مسبقاً
        if ($share->revoked_at !== null) {
            return response()->json([
                'success' => true,
                'message' =>
                    'المشاركة ملغاة مسبقاً.',
            ], 200);
        }

        // تسجيل وقت الإلغاء بتوقيت UTC
        $share->update([

            'revoked_at' =>
                Carbon::now('UTC'),

        ]);

        return response()->json([

            'success' => true,

            'message' =>
                'تم إلغاء مشاركة المتابعة بنجاح.',

            'share_id' =>
                $share->share_id,

            'revoked_at' =>
                $share->revoked_at
                    ->copy()
                    ->setTimezone('Asia/Aden')
                    ->format('Y-m-d H:i:s'),

        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | التحقق من رابط المشاركة ورمز الدخول
    |--------------------------------------------------------------------------
    */

    public function verifyAccess(
        Request $request,
        string $token
    ) {
        // التحقق من رمز الدخول المرسل
        $validated = $request->validate([

            'access_code' => [
                'required',
                'string',
                'digits:6',
            ],

        ]);

        // البحث عن المشاركة باستخدام بصمة الرابط
        $share = CareShare::with('child')
            ->where(
                'token_hash',
                hash('sha256', $token)
            )
            ->first();

        if (!$share) {
            return response()->json([
                'success' => false,
                'message' =>
                    'رابط المشاركة غير صالح.',
            ], 404);
        }

        // التحقق من رمز الدخول
        if (!Hash::check(
            $validated['access_code'],
            $share->access_code_hash
        )) {
            return response()->json([
                'success' => false,
                'message' =>
                    'رمز الدخول غير صحيح.',
            ], 422);
        }

        // الوقت الحالي بتوقيت UTC
        $now = Carbon::now('UTC');

        // منع الوصول إلى المشاركة الملغاة
        if ($share->revoked_at !== null) {
            return response()->json([
                'success' => false,
                'message' =>
                    'تم إلغاء هذه المشاركة.',
            ], 410);
        }

        // قراءة وقت البداية المحفوظ بتوقيت UTC
        $startsAt = $share->starts_at->copy()->utc();

        if ($now->lt($startsAt)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لم يبدأ وقت المشاركة بعد.',
            ], 403);
        }

        // قراءة وقت الانتهاء المحفوظ بتوقيت UTC
        $expiresAt = $share->expires_at->copy()->utc();

        if ($now->gte($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'انتهت صلاحية المشاركة.',
            ], 410);
        }

        // =====================================
        // نجاح التحقق
        // =====================================

        return response()->json([

            'success' => true,

            'message' =>
                'تم التحقق من المشاركة بنجاح.',

            'data' => [

                'share_id' =>
                    $share->share_id,

                'child_name' =>
                    $share->child?->name,

                'allow_meals' =>
                    $share->allow_meals,

                'allow_glucose' =>
                    $share->allow_glucose,

                'allow_activities' =>
                    $share->allow_activities,

                'expires_at' =>
                    $expiresAt->copy()
                        ->setTimezone('Asia/Aden')
                        ->format('Y-m-d H:i:s'),

            ],

        ], 200);
    }
}