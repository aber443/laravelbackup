<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Medication;
use App\Models\MedicationActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MedicationController extends Controller
{
    // ===== START: ABEER - Medication API =====

    // التأكد أن الطفل تابع لولي الأمر المسجل
    private function findChild(Request $request, int $childId): Child
    {
        return Child::where('child_id', $childId)
            ->where('user_id', $request->user()->getKey())
            ->firstOrFail();
    }

    // التأكد أن الدواء تابع للطفل الصحيح
    private function findMedication(
        Child $child,
        int $medicationId
    ): Medication {
        return Medication::where('medication_id', $medicationId)
            ->where('child_id', $child->child_id)
            ->firstOrFail();
    }

    // تسجيل نشاط المستخدم المرتبط بالأدوية
    private function recordActivity(
        Request $request,
        Child $child,
        Medication $medication,
        string $action
    ): void {
        MedicationActivity::create([
            'user_id' => $request->user()->getKey(),
            'child_id' => $child->child_id,
            'medication_id' => $medication->medication_id,
            'action' => $action,
            'occurred_at' => now(),
        ]);
    }

    // جلب أدوية الطفل
    public function index(Request $request, int $childId)
    {
        $child = $this->findChild($request, $childId);

        $medications = Medication::where('child_id', $child->child_id)
            ->orderByDesc('medication_id')
            ->get();

        return response()->json([
            'medications' => $medications,
        ]);
    }

    // إضافة دواء جديد
    public function store(Request $request, int $childId)
    {
        $child = $this->findChild($request, $childId);

        $validated = $request->validate(
            $this->medicationRules()
        );

        $validated['child_id'] = $child->child_id;

        $medication = DB::transaction(function () use (
            $request,
            $child,
            $validated
        ) {
            $medication = Medication::create($validated);

            $this->recordActivity(
                $request,
                $child,
                $medication,
                'created'
            );

            return $medication;
        });

        return response()->json([
            'message' => 'تم حفظ الدواء بنجاح',
            'medication' => $medication,
        ], 201);
    }

    // تعديل دواء موجود
    public function update(
        Request $request,
        int $childId,
        int $medicationId
    ) {
        $child = $this->findChild($request, $childId);

        $medication = $this->findMedication(
            $child,
            $medicationId
        );

        $validated = $request->validate(
            $this->medicationRules()
        );

        $medication = DB::transaction(function () use (
            $request,
            $child,
            $medication,
            $validated
        ) {
            $medication->update($validated);
            $medication->refresh();

            $this->recordActivity(
                $request,
                $child,
                $medication,
                'updated'
            );

            return $medication;
        });

        return response()->json([
            'message' => 'تم تحديث بيانات الدواء بنجاح',
            'medication' => $medication,
        ]);
    }

    // حذف دواء
    public function destroy(
        Request $request,
        int $childId,
        int $medicationId
    ) {
        $child = $this->findChild($request, $childId);

        $medication = $this->findMedication(
            $child,
            $medicationId
        );

        DB::transaction(function () use (
            $request,
            $child,
            $medication
        ) {
            $this->recordActivity(
                $request,
                $child,
                $medication,
                'deleted'
            );

            $medication->delete();
        });

        return response()->json([
            'message' => 'تم حذف الدواء بنجاح',
        ]);
    }

    // التحقق من بيانات الدواء
    private function medicationRules(): array
    {
        return [
            'medication_name' => [
                'required',
                'string',
                'min:2',
                'max:60',
            ],

            'insulin_type' => [
                'nullable',
                Rule::requiredIf(
                    fn () => request('medication_name') === 'Insulin'
                ),
                Rule::in(['Short', 'Long', 'Mixed']),
            ],

            'dosage' => [
                'required',
                'numeric',
                'gt:0',
                'lte:9999',
            ],

            'dosage_unit' => [
                'required',
                Rule::in([
                    'IU/ML',
                    'UI',
                    'U',
                    'Ul',
                    'mg',
                    'mL',
                ]),
            ],

            // بيانات التكرار القديمة اختيارية
            'frequency' => [
                'nullable',
                Rule::in([
                    'مرة يوميًا',
                    'مرتان يوميًا',
                    '3 مرات يوميًا',
                ]),
            ],

            // أوقات التذكير اختيارية
            'reminder_times' => [
                'nullable',
                'array',
                'max:3',
            ],

            'reminder_times.*' => [
                'required',
                'date_format:H:i:s',
            ],

            // تاريخ بدء الدواء اختياري
            'start_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            // التذكير اختياري
            'reminder_enabled' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    // ===== END: ABEER - Medication API =====
}