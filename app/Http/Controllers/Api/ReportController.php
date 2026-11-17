<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Report;
use App\Services\MealAnalysisService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * إنشاء تقرير للطفل حسب النوع والفترة المحددة.
     */
    public function generate(Request $request)
    {
        $user = $request->user();

        // التحقق من صلاحية ولي الأمر
        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بعرض التقارير.',
            ], 403);
        }

        // التحقق من بيانات الطلب
        $validator = Validator::make(
            $request->all(),
            [
                'child_id' => ['required', 'integer'],

                'report_type' => [
                    'required',
                    'in:comprehensive,activity,exams',
                ],

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
                'message' => 'بيانات التقرير غير صحيحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $start = Carbon::parse(
            $data['start_date']
        )->toDateString();

        $end = Carbon::parse(
            $data['end_date']
        )->toDateString();

        // منع اختيار فترة مستقبلية
        $today = Carbon::now('Asia/Aden')->toDateString();

        if ($end > $today) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إنشاء تقرير لفترة مستقبلية.',
            ], 422);
        }

        // التحقق من ملكية الطفل
        $child = Child::where(
            'child_id',
            $data['child_id']
        )
            ->where('user_id', $user->user_id)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'الطفل غير موجود أو لا تملكين صلاحية الوصول إليه.',
            ], 404);
        }

        // تحديد نوع التقرير
        $report = match ($data['report_type']) {

            'comprehensive' => $this->comprehensiveReport(
                $child,
                $start,
                $end
            ),

            'activity' => $this->activityReport(
                $child->child_id,
                $start,
                $end
            ),

            'exams' => $this->examsReport(
                $child->child_id,
                $start,
                $end
            ),
        };

        return response()->json([
            'success' => true,

            'message' => 'تم إنشاء التقرير بنجاح.',

            'report' => [
                'report_type' => $data['report_type'],

                // بيانات الطفل
                'child' => [
                    'child_id' => $child->child_id,
                    'name' => $child->name,
                    'birth_date' => $child->birth_date,
                ],

                // فترة التقرير
                'period' => [
                    'start_date' => $start,
                    'end_date' => $end,
                ],

                // بيانات التقرير
                'data' => $report,
            ],
        ], 200);
    }


    // =========================================
    // حفظ التقرير الصحي
    // POST /api/reports
    // =========================================

    public function store(Request $request)
    {
        $user = $request->user();

        // التحقق من صلاحية ولي الأمر
        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بحفظ التقارير.',
            ], 403);
        }

        // التحقق من بيانات التقرير
        $validator = Validator::make(
            $request->all(),
            [
                'child_id' => ['required', 'integer'],

                'report_type' => [
                    'required',
                    'in:comprehensive,activity,exams,medical_exams',
                ],

                'title' => [
                    'nullable',
                    'string',
                    'max:150',
                ],

                'start_date' => [
                    'required',
                    'date_format:Y-m-d',
                ],

                'end_date' => [
                    'required',
                    'date_format:Y-m-d',
                    'after_or_equal:start_date',
                ],

                'summary' => [
                    'nullable',
                    'string',
                ],

                'report_data' => [
                    'required',
                    'array',
                ],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات التقرير غير صحيحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // التحقق من ملكية الطفل
        $child = Child::where(
            'child_id',
            $data['child_id']
        )
            ->where('user_id', $user->user_id)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'الطفل غير موجود أو لا تملكين صلاحية الوصول إليه.',
            ], 404);
        }

        // توحيد اسم نوع التقرير مع قاعدة البيانات
        $reportType = $data['report_type'] === 'exams'
            ? 'medical_exams'
            : $data['report_type'];

        // العناوين الافتراضية
        $titles = [
            'comprehensive' => 'التقرير الصحي الشامل',
            'activity' => 'تقرير النشاط البدني',
            'medical_exams' => 'تقرير الفحوصات الطبية',
        ];

        $title = !empty($data['title'])
            ? trim($data['title'])
            : $titles[$reportType];

        // حفظ التقرير
        $savedReport = Report::create([

            'child_id' => $child->child_id,

            'report_type' => $reportType,

            'title' => $title,

            'start_date' => $data['start_date'],

            'end_date' => $data['end_date'],

            'summary' => $data['summary'] ?? null,

            // حفظ بيانات التقرير والتحليل كاملة
            'report_data' => $data['report_data'],

            'generated_at' => Carbon::now('Asia/Aden'),

        ]);

        $savedReport->load([
            'child' => function ($query) {
                $query->select('child_id', 'name');
            }
        ]);

        return response()->json([
            'success' => true,

            'message' => 'تم حفظ التقرير بنجاح.',

            'report' => $savedReport,

        ], 201);
    }


    // =========================================
    // جلب التقارير المحفوظة
    // GET /api/reports
    // =========================================

    public function index(Request $request)
    {
        $user = $request->user();

        // التحقق من صلاحية ولي الأمر
        if (
            !$user ||
            $user->role !== 'parent' ||
            !$user->is_active
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح بعرض التقارير.',
            ], 403);
        }

        // التحقق من الفلاتر الاختيارية
        $validator = Validator::make(
            $request->query(),
            [
                'child_id' => ['nullable', 'integer'],

                'report_type' => [
                    'nullable',
                    'in:comprehensive,activity,exams,medical_exams',
                ],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات البحث غير صحيحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $filters = $validator->validated();

        // جلب تقارير أطفال ولي الأمر فقط
        $query = Report::with([
            'child' => function ($query) {
                $query->select('child_id', 'name');
            }
        ])->whereHas(
            'child',
            function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            }
        );

        // فلترة حسب الطفل
        if (!empty($filters['child_id'])) {
            $query->where(
                'child_id',
                $filters['child_id']
            );
        }

        // فلترة حسب نوع التقرير
        if (!empty($filters['report_type'])) {

            $reportType = $filters['report_type'] === 'exams'
                ? 'medical_exams'
                : $filters['report_type'];

            $query->where(
                'report_type',
                $reportType
            );
        }

        $reports = $query
            ->orderByDesc('generated_at')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,

            'message' => 'تم جلب التقارير بنجاح.',

            'total' => $reports->count(),

            'reports' => $reports,
        ], 200);
    }


    /**
     * التقرير الصحي الشامل.
     *
     * يجمع السجلات الصحية ويستدعي خدمة تحليل
     * العلاقة الزمنية بين الوجبات وقراءات السكر.
     */
    private function comprehensiveReport(
        Child $child,
        string $start,
        string $end
    ): array {

        $childId = $child->child_id;

        // =====================================
        // 1. قراءات السكر
        // =====================================

        $glucoseReadings = $this->getRecords(
            'glucose_readings',
            $childId,
            $start,
            $end,
            'reading_date',
            ['reading_time']
        );

        // =====================================
        // 2. الوجبات
        // =====================================

        $meals = $this->getRecords(
            'meals',
            $childId,
            $start,
            $end,
            'meal_date',
            ['meal_time']
        );

        // تجهيز أسماء الأطعمة من حقل ingredients
        $meals = $meals->map(function ($meal) {

            // فك ترميز JSON
            $ingredients = $this->decodeIngredients(
                $meal->ingredients ?? null
            );

            // استخراج أسماء جميع الأطعمة
            $foodNames = collect($ingredients)
                ->flatten()
                ->filter(function ($food) {

                    return is_string($food) &&
                        trim($food) !== '';
                })
                ->values()
                ->all();

            // الاحتفاظ بالمجموعات الغذائية
            $meal->ingredients = $ingredients;

            // قائمة أسماء الأطعمة
            $meal->food_names = $foodNames;

            // اسم الوجبة للعرض في Flutter
            $meal->meal_name = !empty($foodNames)
                ? implode('، ', $foodNames)
                : 'غير متوفر';

            return $meal;
        });

        // =====================================
        // 3. الأدوية
        // =====================================

        $medications = DB::table('medications')
            ->where('child_id', $childId)
            ->whereDate('start_date', '<=', $end)
            ->orderBy('start_date')
            ->get();

        // =====================================
        // 4. الأنشطة
        // =====================================

        $activities = $this->getRecords(
            'activities',
            $childId,
            $start,
            $end,
            'activity_date',
            ['activity_time']
        );

        // =====================================
        // 5. الفحوصات الطبية
        // =====================================

        $medicalExams = $this->getRecords(
            'medical_exams',
            $childId,
            $start,
            $end,
            'exam_date'
        );

        // =====================================
        // 6. تحليل العلاقة بين الوجبات والسكر
        // =====================================

        $mealAnalysisService = app(
            MealAnalysisService::class
        );

        $mealAnalysis = $mealAnalysisService->analyze(
            $child,
            $start,
            $end
        );

        // =====================================
        // 7. النتيجة النهائية
        // =====================================

        return [

            'glucose_readings' => $glucoseReadings,

            'meals' => $meals,

            'medications' => $medications,

            'activities' => $activities,

            'medical_exams' => $medicalExams,

            // نتائج التحليل الصحي
            'health_analysis' => [

                'meal_analysis' => $mealAnalysis,

            ],
        ];
    }


    /**
     * فك ترميز مكونات الوجبة.
     */
    private function decodeIngredients($ingredients): array
    {
        if (is_array($ingredients)) {
            return $ingredients;
        }

        if (
            $ingredients === null ||
            $ingredients === ''
        ) {
            return [];
        }

        if (is_string($ingredients)) {

            $decoded = json_decode(
                $ingredients,
                true
            );

            if (
                json_last_error() === JSON_ERROR_NONE &&
                is_array($decoded)
            ) {
                return $decoded;
            }
        }

        return [];
    }


    /**
     * تقرير النشاط.
     */
    private function activityReport(
        int $childId,
        string $start,
        string $end
    ): array {

        $activities = $this->getRecords(
            'activities',
            $childId,
            $start,
            $end,
            'activity_date',
            ['activity_time']
        );

        return [
            'total_activities' => $activities->count(),

            'activities' => $activities,
        ];
    }


    /**
     * تقرير الفحوصات الطبية.
     */
    private function examsReport(
        int $childId,
        string $start,
        string $end
    ): array {

        $exams = $this->getRecords(
            'medical_exams',
            $childId,
            $start,
            $end,
            'exam_date'
        );

        return [
            'total_exams' => $exams->count(),

            'exams' => $exams,
        ];
    }


    /**
     * جلب السجلات من قاعدة البيانات.
     */
    private function getRecords(
        string $table,
        int $childId,
        string $start,
        string $end,
        string $dateColumn,
        array $orderColumns = []
    ) {

        $query = DB::table($table)
            ->where('child_id', $childId)
            ->whereBetween(
                $dateColumn,
                [$start, $end]
            )
            ->orderBy($dateColumn);

        foreach ($orderColumns as $column) {
            $query->orderBy($column);
        }

        return $query->get();
    }
}