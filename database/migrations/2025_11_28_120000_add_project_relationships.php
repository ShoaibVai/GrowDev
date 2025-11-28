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
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('type', ['solo', 'team'])->default('solo')->after('status');
            $table->foreignId('team_id')->nullable()->after('type')->constrained()->nullOnDelete();
            $table->date('start_date')->nullable()->after('team_id');
            $table->date('end_date')->nullable()->after('start_date');
            $table->enum('source', ['auto', 'manual'])->default('auto')->after('end_date');
        });

        Schema::table('srs_documents', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('user_id')->constrained('projects')->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropConstrainedForeignId('team_id');
            $table->dropColumn(['start_date', 'end_date', 'source']);
        });

        Schema::table('srs_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('project_id');
        });

    }
};
