<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_insights', function (Blueprint $table) {
            $table->id('health_insight_id');

            $table->foreignId('child_id')
                ->constrained('children', 'child_id')
                ->cascadeOnDelete();

            $table->string('insight_type', 100);

            $table->string('title', 150);

            $table->text('summary');

            $table->json('recommendations')->nullable();

            $table->json('analysis_data')->nullable();

            $table->enum('risk_level', [
                'normal',
                'low',
                'moderate',
                'high',
                'critical',
            ])->nullable();

            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_insights');
    }
};