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
		Schema::create('diagrams', function (Blueprint $table) {
			$table->id();
			$table->foreignId('documentation_id')->constrained('documentations')->cascadeOnDelete();
			$table->enum('type', ['flowchart', 'sequence', 'class', 'gantt', 'er', 'state', 'pie']);
			$table->text('mermaid_syntax');
			$table->string('title')->nullable();
			$table->text('description')->nullable();
			$table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
			$table->timestamps();

			$table->index('type');
			$table->index('created_by');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('diagrams');
	}
};
