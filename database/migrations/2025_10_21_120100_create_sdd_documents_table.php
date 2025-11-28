<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Legacy SDD tables were retired in November 2025.
	 * This migration remains so earlier batches continue to run,
	 * but it intentionally performs no schema changes.
	 */
	public function up(): void
	{
		// no-op
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		// no-op
	}
};
