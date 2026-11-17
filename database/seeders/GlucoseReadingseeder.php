<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Child;
use App\Models\GlucoseReading;
use Carbon\Carbon;

class GlucoseReadingSeeder extends Seeder
{
    public function run(): void
    {
        // التأكد من وجود الطفل
        $child = Child::find(1);

        if (!$child) {
            $this->command->error('Child with ID 1 was not found.');
            return;
        }

        // قراءات تجريبية لآخر 7 أيام
        $readings = [
            [95,  'Before Meal', 'Before Breakfast'],
            [120, 'After Meal',  'After Lunch'],
            [145, 'Before Meal', 'Before Dinner'],
            [110, 'After Meal',  'After Breakfast'],
            [165, 'After Meal',  'After Lunch'],
            [130, 'Before Meal', 'Before Dinner'],
            [105, 'Before Meal', 'Before Breakfast'],
        ];

        foreach ($readings as $index => $reading) {

            $measuredAt = Carbon::today()
                ->subDays(6 - $index)
                ->setTime(9, 0);

            GlucoseReading::updateOrCreate(
                [
                    'child_id' => $child->id,
                    'measured_at' => $measuredAt,
                    'notes' => 'TEST DATA - Dashboard',
                ],
                [
                    'glucose_level' => $reading[0],
                    'measurement_type' => $reading[1],
                    'meal' => $reading[2],
                    'unit' => 'mg/dL',
                ]
            );
        }

        $this->command->info(
            '7 test glucose readings seeded successfully.'
        );
    }
}