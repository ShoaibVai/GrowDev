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
		Schema::create('documentations', function (Blueprint $table) {
			$table->id();
			$table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
			$table->foreignId('template_id')->constrained('documentation_templates')->cascadeOnDelete();
			$table->string('title', 255);
			$table->longText('content');
			$table->unsignedInteger('version')->default(1);
			$table->enum('status', ['draft', 'review', 'approved'])->default('draft');
			$table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
			$table->timestamps();

			$table->index('status');
			$table->index('created_by');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('documentations');
	}
};
