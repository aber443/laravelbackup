
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_exams', function (Blueprint $table) {
            $table->id('medical_exam_id');

            $table->foreignId('child_id')
                ->constrained('children', 'child_id')
                ->cascadeOnDelete();

            $table->string('exam_name', 80);
            $table->date('exam_date');

            $table->string('attachment_path');
           

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_exams');
    }
};
