<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Child;
use App\Models\Meal;
use App\Models\GlucoseReading;
use App\Models\Medication;

class HealthAnalysisTestSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 1. التحقق من ولي الأمر
            $parent = User::where('user_id', 1)
                ->where('role', 'parent')
                ->where('is_active', 1)
                ->firstOrFail();

            // 2. إنشاء الطفلة التجريبية
            $child = Child::firstOrCreate(
                [
                    'user_id' => $parent->user_id,
                    'name' => 'سارة - اختبار التحليل',
                ],
                [
                    'gender' => 'أنثى',
                    'birth_date' => '2021-05-15',
                    'diagnosis_date' => '2024-01-10',
                    'diabetes_type' => 'النوع الأول',
                    'notes' => 'بيانات تجريبية لاختبار التحليل فقط.',
                ]
            );

            // 3. بيانات الأسبوع
            $days = [
                [
                    'date' => '2026-10-26',
                    'glucose' => [105, 128, 154],
                    'foods' => [
                        [
                            'type' => 'الإفطار',
                            'time' => '07:30:00',
                            'ingredients' => [
                                'النشويات' => ['خبز'],
                                'البروتينات' => ['بيض'],
                                'الخضروات' => ['خيار'],
                            ],
                        ],
                        [
                            'type' => 'الغداء',
                            'time' => '13:00:00',
                            'ingredients' => [
                                'النشويات' => ['أرز'],
                                'البروتينات' => ['دجاج'],
                                'الخضروات' => ['سلطة'],
                            ],
                        ],
                        [
                            'type' => 'العشاء',
                            'time' => '19:00:00',
                            'ingredients' => [
                                'البروتينات' => ['جبن'],
                                'النشويات' => ['خبز'],
                            ],
                        ],
                    ],
                ],
                [
                    'date' => '2026-10-27',
                    'glucose' => [98, 122, 146],
                    'foods' => [
                        [
                            'type' => 'الإفطار',
                            'time' => '07:30:00',
                            'ingredients' => [
                                'البروتينات' => ['لبنة'],
                                'النشويات' => ['خبز'],
                                'الخضروات' => ['طماطم'],
                            ],
                        ],
                        [
                            'type' => 'الغداء',
                            'time' => '13:00:00',
                            'ingredients' => [
                                'البروتينات' => ['سمك'],
                                'النشويات' => ['أرز'],
                                'الخضروات' => ['خضار'],
                            ],
                        ],
                        [
                            'type' => 'العشاء',
                            'time' => '19:00:00',
                            'ingredients' => [
                                'البروتينات' => ['عدس'],
                                'النشويات' => ['خبز'],
                            ],
                        ],
                    ],
                ],
                [
                    'date' => '2026-10-28',
                    'glucose' => [112, 135, 168],
                    'foods' => [
                        [
                            'type' => 'الإفطار',
                            'time' => '07:30:00',
                            'ingredients' => [
                                'البروتينات' => ['بيض'],
                                'النشويات' => ['توست'],
                            ],
                        ],
                        [
                            'type' => 'الغداء',
                            'time' => '13:00:00',
                            'ingredients' => [
                                'البروتينات' => ['لحم'],
                                'النشويات' => ['أرز'],
                                'الخضروات' => ['سلطة'],
                            ],
                        ],
                        [
                            'type' => 'العشاء',
                            'time' => '19:00:00',
                            'ingredients' => [
                                'البروتينات' => ['تونة'],
                                'النشويات' => ['خبز'],
                            ],
                        ],
                    ],
                ],
                [
                    'date' => '2026-10-29',
                    'glucose' => [101, 119, 142],
                    'foods' => [
                        [
                            'type' => 'الإفطار',
                            'time' => '07:30:00',
                            'ingredients' => [
                                'البروتينات' => ['جبن'],
                                'النشويات' => ['خبز'],
                            ],
                        ],
                        [
                            'type' => 'الغداء',
                            'time' => '13:00:00',
                            'ingredients' => [
                                'البروتينات' => ['دجاج'],
                                'النشويات' => ['بطاطس'],
                                'الخضروات' => ['سلطة'],
                            ],
                        ],
                        [
                            'type' => 'العشاء',
                            'time' => '19:00:00',
                            'ingredients' => [
                                'منتجات الألبان' => ['زبادي'],
                                'الفواكه' => ['موز'],
                            ],
                        ],
                    ],
                ],
                [
                    'date' => '2026-10-30',
                    'glucose' => [108, 130, 159],
                    'foods' => [
                        [
                            'type' => 'الإفطار',
                            'time' => '07:30:00',
                            'ingredients' => [
                                'البروتينات' => ['فول'],
                                'النشويات' => ['خبز'],
                                'الخضروات' => ['خيار'],
                            ],
                        ],
                        [
                            'type' => 'الغداء',
                            'time' => '13:00:00',
                            'ingredients' => [
                                'البروتينات' => ['سمك'],
                                'النشويات' => ['أرز'],
                                'الخضروات' => ['سلطة'],
                            ],
                        ],
                        [
                            'type' => 'العشاء',
                            'time' => '19:00:00',
                            'ingredients' => [
                                'البروتينات' => ['بيض'],
                                'النشويات' => ['خبز'],
                            ],
                        ],
                    ],
                ],
                [
                    'date' => '2026-10-31',
                    'glucose' => [96, 125, 148],
                    'foods' => [
                        [
                            'type' => 'الإفطار',
                            'time' => '07:30:00',
                            'ingredients' => [
                                'البروتينات' => ['بيض'],
                                'النشويات' => ['خبز'],
                            ],
                        ],
                        [
                            'type' => 'الغداء',
                            'time' => '13:00:00',
                            'ingredients' => [
                                'البروتينات' => ['دجاج'],
                                'النشويات' => ['مكرونة'],
                                'الخضروات' => ['خضار'],
                            ],
                        ],
                        [
                            'type' => 'العشاء',
                            'time' => '19:00:00',
                            'ingredients' => [
                                'البروتينات' => ['عدس'],
                                'منتجات الألبان' => ['زبادي'],
                            ],
                        ],
                    ],
                ],
                [
                    'date' => '2026-11-01',
                    'glucose' => [110, 138, 172],
                    'foods' => [
                        [
                            'type' => 'الإفطار',
                            'time' => '07:30:00',
                            'ingredients' => [
                                'البروتينات' => ['لبنة'],
                                'النشويات' => ['خبز'],
                                'الخضروات' => ['خيار'],
                            ],
                        ],
                        [
                            'type' => 'الغداء',
                            'time' => '13:00:00',
                            'ingredients' => [
                                'البروتينات' => ['لحم'],
                                'النشويات' => ['بطاطس'],
                                'الخضروات' => ['سلطة'],
                            ],
                        ],
                        [
                            'type' => 'العشاء',
                            'time' => '19:00:00',
                            'ingredients' => [
                                'البروتينات' => ['دجاج'],
                                'النشويات' => ['خبز'],
                            ],
                        ],
                    ],
                ],
            ];

            // 4. إنشاء الوجبات وقراءات السكر
            foreach ($days as $day) {

                $lunchMealId = null;

                foreach ($day['foods'] as $food) {

                    $meal = Meal::updateOrCreate(
                        [
                            'child_id' => $child->child_id,
                            'meal_type' => $food['type'],
                            'meal_date' => $day['date'],
                            'meal_time' => $food['time'],
                        ],
                        [
                            'ingredients' => $food['ingredients'],
                            'notes' => 'بيانات تجريبية للتحليل.',
                        ]
                    );

                    if ($food['type'] === 'الغداء') {
                        $lunchMealId = $meal->meal_id;
                    }
                }

                $readingData = [
                    [
                        'type' => 'صائم',
                        'time' => '07:00:00',
                        'value' => $day['glucose'][0],
                        'meal_id' => null,
                        'meal_status' => null,
                    ],
                    [
                        'type' => 'قبل الوجبة',
                        'time' => '12:30:00',
                        'value' => $day['glucose'][1],
                        'meal_id' => null,
                        'meal_status' => null,
                    ],
                    [
                        'type' => 'بعد الوجبة',
                        'time' => '15:00:00',
                        'value' => $day['glucose'][2],
                        'meal_id' => $lunchMealId,
                        'meal_status' => 'الوجبة مسجلة في التطبيق',
                    ],
                ];

                foreach ($readingData as $reading) {

                    GlucoseReading::updateOrCreate(
                        [
                            'child_id' => $child->child_id,
                            'reading_type' => $reading['type'],
                            'reading_date' => $day['date'],
                            'reading_time' => $reading['time'],
                        ],
                        [
                            'glucose_level' => $reading['value'],
                            'unit' => 'mg/dL',
                            'meal_id' => $reading['meal_id'],
                            'meal_status' => $reading['meal_status'],
                        ]
                    );
                }
            }

            // 5. إنشاء الأدوية التجريبية
            Medication::updateOrCreate(
                [
                    'child_id' => $child->child_id,
                    'medication_name' => 'Insulin',
                ],
                [
                    'insulin_type' => 'Short',
                    'dosage' => 1.00,
                    'dosage_unit' => 'U',
                    'frequency' => '3 مرات يوميًا',
                    'reminder_times' => [
                        '07:00:00',
                        '13:00:00',
                        '19:00:00',
                    ],
                    'start_date' => '2026-10-26',
                    'reminder_enabled' => true,
                ]
            );

            Medication::updateOrCreate(
                [
                    'child_id' => $child->child_id,
                    'medication_name' => 'Metformin',
                ],
                [
                    'insulin_type' => null,
                    'dosage' => 1.00,
                    'dosage_unit' => 'mg',
                    'frequency' => 'مرتان يوميًا',
                    'reminder_times' => [
                        '08:00:00',
                        '20:00:00',
                    ],
                    'start_date' => '2026-10-26',
                    'reminder_enabled' => true,
                ]
            );

            // 6. عرض النتيجة
            $this->command->info(
                'تم تجهيز بيانات الطفلة التجريبية بنجاح.'
            );

            $this->command->info(
                'Child ID: ' . $child->child_id
            );

            $this->command->info(
                'Meals: ' . Meal::where(
                    'child_id',
                    $child->child_id
                )->count()
            );

            $this->command->info(
                'Glucose readings: ' . GlucoseReading::where(
                    'child_id',
                    $child->child_id
                )->count()
            );

            $this->command->info(
                'Medications: ' . Medication::where(
                    'child_id',
                    $child->child_id
                )->count()
            );
        });
    }
}
