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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->text('professional_summary')->nullable()->after('phone_number');
            $table->string('location')->nullable()->after('professional_summary');
            $table->string('website')->nullable()->after('location');
            $table->string('linkedin_url')->nullable()->after('website');
            $table->string('github_url')->nullable()->after('linkedin_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'professional_summary',
                'location',
                'website',
                'linkedin_url',
                'github_url',
            ]);
        });
    }
};
