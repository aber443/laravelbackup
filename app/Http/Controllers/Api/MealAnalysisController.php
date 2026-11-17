<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Services\MealAnalysisService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealAnalysisController extends Controller
{
    public function show(
        Request $request,
        MealAnalysisService $analysisService
    ) {
        // التأكد من ولي الأمر
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بعرض تحليل الوجبات.',
            ], 403);
        }

        // التحقق من بيانات الطلب
        $validator = Validator::make(
            $request->all(),
            [
                'child_id' => ['required', 'integer'],

                'start_date' => [
                    'required',
                    'date_format:Y-m-d',
                ],

                'end_date' => [
                    'required',
                    'date_format:Y-m-d',
                    'after_or_equal:start_date',
                ],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات طلب تحليل الوجبات غير صحيحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // التأكد من ملكية الطفل
        $child = Child::where(
            'child_id',
            $data['child_id']
        )
            ->where('user_id', $user->user_id)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'الطفل غير موجود أو لا تملكين صلاحية عرض بياناته.',
            ], 404);
        }

        // منع تحليل فترة مستقبلية
        $today = Carbon::now('Asia/Aden')->toDateString();

        if ($data['end_date'] > $today) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تحليل فترة مستقبلية.',
            ], 422);
        }

        // تنفيذ تحليل الوجبات
        $analysis = $analysisService->analyze(
            $child,
            $data['start_date'],
            $data['end_date']
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تحليل الوجبات وقراءات السكر بنجاح.',
            'analysis' => $analysis,
        ], 200);
    }
}