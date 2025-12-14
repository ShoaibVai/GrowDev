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
            $table->index('status');
            $table->index('user_id');
        });

        Schema::table('srs_documents', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('srs_documents', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['project_id']);
        });
    }
};
