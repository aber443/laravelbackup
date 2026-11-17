<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users', 'user_id');

            $table->string('type', 20);
            $table->text('message')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('status', 20)->default('new');
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};