
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {

            $table->id('child_id');

            // ولي الأمر صاحب الطفل
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->cascadeOnDelete();

            // الاسم الكامل: الأول + الثاني + اللقب
            $table->string('name', 62);

            // الجنس: ولد أو بنت
            $table->string('gender', 4);

            // تاريخ الميلاد
            $table->date('birth_date');

            // نوع السكري: الأول أو الثاني أو نوع آخر
            $table->string('diabetes_type', 30);

            // تاريخ الإصابة بالسكري
            $table->date('diagnosis_date');

            // الملاحظات الإضافية (اختياري)
            $table->text('notes')->nullable();

            // مسار صورة الطفل (اختياري)
            $table->string('image_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
