<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('sprint_id')->nullable()->after('project_id')->constrained()->nullOnDelete();
            $table->integer('sort_order')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sprint_id');
            $table->dropColumn('sort_order');
        });
    }
};
