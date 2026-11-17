<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {

            // حذف الفهرس المرتبط برمز الاستجابة
            $table->dropIndex('system_logs_status_code_index');

            // حذف الحقول غير الضرورية للداشبورد
            $table->dropColumn([
                'source',
                'method',
                'url',
                'status_code',
                'duration_ms',
                'ip_address',
                'user_agent',
                'metadata',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {

            $table->string('source', 30)->nullable();
            $table->string('method', 10)->nullable();
            $table->text('url')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();

            $table->index('status_code');
        });
    }
};