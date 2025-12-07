<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add specialization and seniority to roles
        Schema::table('roles', function (Blueprint $table) {
            $table->string('category')->nullable()->after('description'); // e.g., development, design, management
            $table->string('seniority_level')->nullable()->after('category'); // junior, mid, senior, lead
            $table->boolean('is_system_role')->default(false)->after('seniority_level'); // System-defined roles
        });

        // Add role requirement and effort estimate to tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('required_role_id')->nullable()->after('assigned_to')->constrained('roles')->nullOnDelete();
            $table->integer('estimated_hours')->nullable()->after('due_date');
            $table->text('ai_generated_description')->nullable()->after('description');
            $table->boolean('is_ai_generated')->default(false)->after('ai_generated_description');
        });

        // Create task dependencies table
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('depends_on_task_id')->constrained('tasks')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['task_id', 'depends_on_task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_dependencies');

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('required_role_id');
            $table->dropColumn(['estimated_hours', 'ai_generated_description', 'is_ai_generated']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['category', 'seniority_level', 'is_system_role']);
        });
    }
};
