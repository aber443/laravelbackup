<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('glucose_readings', function (Blueprint $table) {
            $table->id('glucose_reading_id');

            $table->foreignId('child_id')
                ->constrained('children', 'child_id')
                ->cascadeOnDelete();

            $table->string('reading_type');
            $table->decimal('glucose_level', 8, 2);
            $table->string('unit')->default('mg/dL');
            $table->date('reading_date');
            $table->time('reading_time');

            $table->string('meal_status')->nullable();

            $table->foreignId('meal_id')
                ->nullable()
                ->constrained('meals', 'meal_id')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('glucose_readings');
    }
};