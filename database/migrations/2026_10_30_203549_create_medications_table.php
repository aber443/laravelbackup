
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id('medication_id');

            $table->foreignId('child_id')
                ->constrained('children', 'child_id')
                ->cascadeOnDelete();

            // اسم الدواء
            $table->string('medication_name', 60);

            // نوع الأنسولين (إذا كان الدواء Insulin)
            $table->string('insulin_type')->nullable();

            // الجرعة ووحدتها
            $table->decimal('dosage', 6, 2);
            $table->string('dosage_unit', 20);

            // عدد مرات تناول الدواء يوميًا
            $table->string('frequency');

            // أوقات الجرعات (وقت أو وقتان أو ثلاثة)
            $table->json('reminder_times');

            // تاريخ بدء الدواء
            $table->date('start_date');

            // تفعيل أو إيقاف التذكير
            $table->boolean('reminder_enabled')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
