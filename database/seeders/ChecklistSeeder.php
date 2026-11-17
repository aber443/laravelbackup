<?php
namespace Database\Seeders;

use App\Models\Checklist;
use Illuminate\Database\Seeder;

class ChecklistSeeder extends Seeder
{
    public function run(): void
    {
        $checklists = [

            [
                'title' => 'مراقبة موارد النظام',

                'items' => [
                    'التحقق من حالة الخادم',
                    'مراجعة استهلاك المعالج (CPU)',
                    'مراجعة استهلاك الذاكرة (RAM)',
                    'مراجعة مساحة التخزين المتاحة',
                ],
            ],

            [
                'title' => 'سلامة الخدمات والاتصال',

                'items' => [
                    'التحقق من اتصال قاعدة البيانات',
                    'التحقق من استجابة واجهات API',
                    'التحقق من عمل خدمات التطبيق',
                    'مراجعة الخدمات التي تواجه مشكلات',
                ],
            ],

            [
                'title' => 'الأمان وسجلات النظام',

                'items' => [
                    'مراجعة الأخطاء التقنية',
                    'مراجعة التنبيهات الأمنية',
                    'مراجعة محاولات تسجيل الدخول الفاشلة',
                    'مراجعة طلبات API الفاشلة',
                ],
            ],

            [
                'title' => 'مراقبة خدمات التطبيق',

                'items' => [
                    'متابعة نجاح إرسال الإشعارات',
                    'مراجعة حالات فشل إرسال الإشعارات',
                    'مراجعة المستخدمين دون أجهزة مسجلة',
                    'التحقق من حفظ الإشعارات في قاعدة البيانات',
                ],
            ],

        ];

        foreach ($checklists as $checklistData) {

            $checklist = Checklist::firstOrCreate(
                ['title' => $checklistData['title']]
            );

            foreach ($checklistData['items'] as $itemTitle) {

                $checklist->items()->firstOrCreate([
                    'title' => $itemTitle,
                ]);
            }
        }
    }
}
