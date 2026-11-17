<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id('activity_id');

            $table->foreignId('child_id')
                ->constrained('children', 'child_id')
                ->cascadeOnDelete();

            $table->string('activity_name');
            $table->string('duration');
            $table->string('intensity');
            $table->date('activity_date');
            $table->time('activity_time');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};