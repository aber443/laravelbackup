
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_health_checks', function (Blueprint $table) {
            $table->id();

            $table->string('category', 100);
            $table->string('check_key', 150);

            $table->enum('status', [
                'passed',
                'warning',
                'failed',
            ]);

            $table->string('message', 500)->nullable();
            $table->json('details')->nullable();

            $table->timestamp('checked_at')->useCurrent();

            $table->timestamps();

            $table->index(['category', 'checked_at']);
            $table->index(['check_key', 'checked_at']);
            $table->index(['status', 'checked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_health_checks');
    }
};
