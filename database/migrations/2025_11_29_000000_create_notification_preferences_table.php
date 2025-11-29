<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('email_on_task_assigned')->default(true);
            $table->boolean('email_on_task_status_change')->default(true);
            $table->boolean('email_reminders')->default(true);
            $table->enum('digest_frequency', ['none', 'daily', 'weekly'])->default('none');
            $table->time('digest_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
