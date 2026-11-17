<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {

            $table->id('system_log_id');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users', 'user_id')
                ->nullOnDelete();

            $table->string('event_type', 50);

            $table->string('severity', 20)->default('info');

            $table->string('action', 100);

            $table->text('description')->nullable();

            $table->timestamp('occurred_at')->useCurrent();

            $table->timestamps();

            $table->index(
                ['event_type', 'occurred_at'],
                'system_logs_type_date_index'
            );

            $table->index(
                ['severity', 'occurred_at'],
                'system_logs_severity_date_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};