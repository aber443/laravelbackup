
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id('meal_id');

            $table->foreignId('child_id')
                ->constrained('children', 'child_id')
                ->cascadeOnDelete();

            $table->string('meal_type');

            $table->json('ingredients');

            $table->date('meal_date');

            $table->time('meal_time');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
