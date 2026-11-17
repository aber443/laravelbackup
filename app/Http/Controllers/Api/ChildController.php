<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ChildController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | جلب أطفال ولي الأمر
    | GET /api/children
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
                'message' => 'غير مسموح بعرض بيانات الأطفال.',
            ], 403);
        }

        // جلب أطفال ولي الأمر الحالي فقط
        $children = Child::where('user_id', $user->user_id)
            ->orderBy('child_id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب بيانات الأطفال بنجاح.',
            'children' => $children,
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | إضافة طفل جديد
    | POST /api/children
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
                'message' => 'غير مسموح بإضافة طفل لهذا الحساب.',
            ], 403);
        }

        // التحقق من بيانات الطفل
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:62',
            ],

            'gender' => [
                'required',
                Rule::in(['ولد', 'بنت']),
            ],

            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' .
                    now()->subYear()->toDateString(),
                'after_or_equal:' .
                    now()->subYears(12)->toDateString(),
            ],

            'diabetes_type' => [
                'required',
                'string',
                'max:30',
            ],

            'diagnosis_date' => [
                'required',
                'date',
                'after_or_equal:birth_date',
                'before_or_equal:today',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من بيانات الطفل.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // حفظ صورة الطفل إذا تم اختيارها
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store('children', 'public');
        }

        // حفظ الطفل وربطه بولي الأمر
        $child = Child::create([
            'user_id' => $user->user_id,
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'diabetes_type' => $validated['diabetes_type'],
            'diagnosis_date' => $validated['diagnosis_date'],
            'notes' => $validated['notes'] ?? null,
            'image_path' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ بيانات الطفل بنجاح.',
            'child' => $child,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | تعديل بيانات طفل موجود
    | PUT /api/children/{childId}
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $childId)
    {
        // معرفة ولي الأمر من Token
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بتعديل بيانات الطفل.',
            ], 403);
        }

        // التأكد أن الطفل تابع لولي الأمر الحالي
        $child = Child::where('user_id', $user->user_id)
            ->where('child_id', $childId)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الطفل غير موجود أو لا تملكين صلاحية تعديله.',
            ], 404);
        }

        // التحقق من البيانات المعدلة
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:62',
            ],

            'gender' => [
                'required',
                Rule::in(['ولد', 'بنت']),
            ],

            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' .
                    now()->subYear()->toDateString(),
                'after_or_equal:' .
                    now()->subYears(12)->toDateString(),
            ],

            'diabetes_type' => [
                'required',
                'string',
                'max:30',
            ],

            'diagnosis_date' => [
                'required',
                'date',
                'after_or_equal:birth_date',
                'before_or_equal:today',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من بيانات الطفل.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // تحديث البيانات الأساسية
        $child->name = $validated['name'];
        $child->gender = $validated['gender'];
        $child->birth_date = $validated['birth_date'];
        $child->diabetes_type = $validated['diabetes_type'];
        $child->diagnosis_date = $validated['diagnosis_date'];
        $child->notes = $validated['notes'] ?? null;

        // تحديث الصورة إذا اختار المستخدم صورة جديدة
        $oldImagePath = null;
        $newImagePath = null;

        if ($request->hasFile('image')) {
            $oldImagePath = $child->image_path;

            $newImagePath = $request
                ->file('image')
                ->store('children', 'public');

            $child->image_path = $newImagePath;
        }

        try {
            // حفظ التعديلات في MySQL
            $child->save();
        } catch (\Throwable $e) {
            // حذف الصورة الجديدة إذا فشل حفظ البيانات
            if ($newImagePath !== null) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $e;
        }

        // حذف الصورة القديمة بعد نجاح الحفظ
        if (
            $newImagePath !== null &&
            !empty($oldImagePath) &&
            $oldImagePath !== $newImagePath
        ) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات الطفل بنجاح.',
            'child' => $child->fresh(),
        ], 200);
    }
}
