<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklists', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 150);
            $table->timestamps();
        });

        Schema::create('checklist_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('checklist_id')
                ->constrained('checklists')
                ->cascadeOnDelete();

            $table->string('title', 200);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
        Schema::dropIfExists('checklists');
    }
};