<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->uuid('ai_generation_run_uuid')->nullable()->after('is_ai_generated');
            $table->unsignedSmallInteger('prompt_schema_version')->default(1)->after('ai_generation_run_uuid');

            $table->string('component')->nullable()->after('category');
            $table->string('component_key')->nullable()->after('component');
            $table->json('predicted_files')->nullable()->after('component_key');
            $table->json('interface_contracts')->nullable()->after('predicted_files');
            $table->string('required_role')->nullable()->after('required_role_id');

            $table->boolean('is_scaffold')->default(false)->after('required_role');
            $table->foreignId('scaffold_owner_id')->nullable()->after('is_scaffold')->constrained('users')->nullOnDelete();
            $table->foreignId('scaffold_task_id')->nullable()->after('scaffold_owner_id')->constrained('tasks')->nullOnDelete();
            $table->timestamp('scaffold_merged_at')->nullable()->after('scaffold_task_id');
            $table->json('scaffold_exceptions')->nullable()->after('scaffold_merged_at');

            $table->longText('prompt_section')->nullable()->after('scaffold_exceptions');
            $table->json('prompt_payload')->nullable()->after('prompt_section');
            $table->text('prompt_brief')->nullable()->after('prompt_payload');

            $table->timestamp('assigned_at')->nullable()->after('assigned_to');
            $table->decimal('time_estimate_hours', 8, 2)->nullable()->after('estimated_hours');
            $table->timestamp('due_at')->nullable()->after('due_date');
            $table->string('timer_state', 20)->default('idle')->after('due_at');
            $table->unsignedBigInteger('time_spent_seconds')->default(0)->after('timer_state');
            $table->timestamp('timer_started_at')->nullable()->after('time_spent_seconds');
            $table->timestamp('timer_paused_at')->nullable()->after('timer_started_at');
            $table->timestamp('last_timer_tick_at')->nullable()->after('timer_paused_at');
            $table->foreignId('timer_started_by')->nullable()->after('last_timer_tick_at')->constrained('users')->nullOnDelete();

            $table->timestamp('last_reminded_at')->nullable()->after('timer_started_by');
            $table->timestamp('overdue_escalated_at')->nullable()->after('last_reminded_at');

            $table->index(['project_id', 'component_key', 'is_scaffold'], 'tasks_project_component_scaffold_idx');
            $table->index(['scaffold_task_id', 'status'], 'tasks_scaffold_task_status_idx');
            $table->index(['timer_state', 'last_timer_tick_at'], 'tasks_timer_state_tick_idx');
            $table->index(['due_at', 'status'], 'tasks_due_at_status_idx');
            $table->index(['ai_generation_run_uuid'], 'tasks_ai_generation_run_idx');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_project_component_scaffold_idx');
            $table->dropIndex('tasks_scaffold_task_status_idx');
            $table->dropIndex('tasks_timer_state_tick_idx');
            $table->dropIndex('tasks_due_at_status_idx');
            $table->dropIndex('tasks_ai_generation_run_idx');

            $table->dropConstrainedForeignId('scaffold_owner_id');
            $table->dropConstrainedForeignId('scaffold_task_id');
            $table->dropConstrainedForeignId('timer_started_by');

            $table->dropColumn([
                'ai_generation_run_uuid',
                'prompt_schema_version',
                'component',
                'component_key',
                'predicted_files',
                'interface_contracts',
                'required_role',
                'is_scaffold',
                'scaffold_merged_at',
                'scaffold_exceptions',
                'prompt_section',
                'prompt_payload',
                'prompt_brief',
                'assigned_at',
                'time_estimate_hours',
                'due_at',
                'timer_state',
                'time_spent_seconds',
                'timer_started_at',
                'timer_paused_at',
                'last_timer_tick_at',
                'last_reminded_at',
                'overdue_escalated_at',
            ]);
        });
    }
};
