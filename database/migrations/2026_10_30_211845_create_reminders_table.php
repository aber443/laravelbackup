
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {

            // معرف التذكير
            $table->id('reminder_id');

            // ولي الأمر صاحب التذكير
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->cascadeOnDelete();

            // عنوان التذكير
            $table->string('title', 80);

            // تاريخ ووقت التذكير
            $table->dateTime('remind_at');

            // حالة تفعيل التذكير
            $table->boolean('is_active')->default(true);

            // وقت إرسال الإشعار لمنع تكراره
            $table->dateTime('last_sent_at')->nullable();

            // تاريخ الإنشاء والتحديث
            $table->timestamps();

            $table->index(['is_active', 'remind_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
