<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add implementation_status to functional requirements
        Schema::table('srs_functional_requirements', function (Blueprint $table) {
            $table->string('implementation_status')->default('listed')->after('status');
        });

        // Add implementation_status to non-functional requirements
        Schema::table('srs_non_functional_requirements', function (Blueprint $table) {
            $table->string('implementation_status')->default('listed')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('srs_functional_requirements', function (Blueprint $table) {
            $table->dropColumn('implementation_status');
        });

        Schema::table('srs_non_functional_requirements', function (Blueprint $table) {
            $table->dropColumn('implementation_status');
        });
    }
};
