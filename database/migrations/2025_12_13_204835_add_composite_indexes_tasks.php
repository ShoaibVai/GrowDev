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
        Schema::table('tasks', function (Blueprint $table) {
            // Composite index for common query patterns
            // Used when querying tasks by project and assignee
            $table->index(['project_id', 'assigned_to']);
            
            // Used when querying tasks by project, assignee, and status
            $table->index(['project_id', 'assigned_to', 'status']);
            
            // Used when querying by project and status
            $table->index(['project_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['project_id', 'assigned_to']);
            $table->dropIndex(['project_id', 'assigned_to', 'status']);
            $table->dropIndex(['project_id', 'status']);
        });
    }
};
