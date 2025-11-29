<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('requirement_type')->nullable()->after('category');
            $table->unsignedBigInteger('requirement_id')->nullable()->after('requirement_type');
            
            $table->index(['requirement_type', 'requirement_id']);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['requirement_type', 'requirement_id']);
            $table->dropColumn(['requirement_type', 'requirement_id']);
        });
    }
};
