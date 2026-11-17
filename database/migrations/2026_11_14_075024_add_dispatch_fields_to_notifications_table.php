<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table): void {
            $table->uuid('dispatch_id')->nullable()->index();
            $table->string('recipient_scope', 16)->nullable();
            $table->unsignedBigInteger('sent_by_user_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table): void {
            $table->dropIndex(['dispatch_id']);
            $table->dropIndex(['sent_by_user_id']);

            $table->dropColumn([
                'dispatch_id',
                'recipient_scope',
                'sent_by_user_id',
            ]);
        });
    }
};