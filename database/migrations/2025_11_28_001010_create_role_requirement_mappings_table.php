<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_requirement_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            // Polymorphic relation to requirements
            $table->morphs('requirement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_requirement_mappings');
    }
};
