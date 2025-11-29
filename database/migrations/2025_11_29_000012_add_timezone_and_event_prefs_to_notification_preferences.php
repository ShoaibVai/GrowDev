<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_preferences', function (Blueprint $table) {
            $table->string('timezone')->nullable()->after('digest_time');
            $table->enum('digest_day', ['sun','mon','tue','wed','thu','fri','sat'])->nullable()->after('digest_frequency');
            $table->boolean('email_on_team_invitation')->default(true)->after('email_on_task_assigned');
            $table->boolean('email_on_srs_update')->default(true)->after('email_on_task_status_change');
        });
    }

    public function down(): void
    {
        Schema::table('notification_preferences', function (Blueprint $table) {
            $table->dropColumn(['timezone', 'digest_day', 'email_on_team_invitation', 'email_on_srs_update']);
        });
    }
};
