
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');

            $table->foreignId('child_id')
                ->constrained('children', 'child_id')
                ->cascadeOnDelete();

            $table->enum('report_type', [
                'comprehensive',
                'activity',
                'medical_exams',
            ]);

            $table->string('title', 150);

            $table->date('start_date');
            $table->date('end_date');

            $table->text('summary')->nullable();

            $table->json('report_data')->nullable();

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
