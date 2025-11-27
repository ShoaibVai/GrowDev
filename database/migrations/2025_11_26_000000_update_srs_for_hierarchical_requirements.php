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
        // Update functional requirements table to support hierarchical structure
        Schema::table('srs_functional_requirements', function (Blueprint $table) {
            // Drop the unique constraint on requirement_id to allow hierarchical IDs
            $table->dropUnique(['requirement_id']);
            
            // Add parent_id for hierarchical structure (null means root level)
            $table->foreignId('parent_id')->nullable()->constrained('srs_functional_requirements')->nullOnDelete();
            
            // Add section number for display (1, 1.1, 1.1.1, etc.)
            $table->string('section_number')->after('requirement_id');
            
            // Add acceptance criteria
            $table->text('acceptance_criteria')->nullable()->after('description');
            
            // Add source/stakeholder
            $table->string('source')->nullable()->after('acceptance_criteria');
            
            // Add status tracking
            $table->enum('status', ['draft', 'review', 'approved', 'implemented', 'verified'])->default('draft');
        });

        // Create non-functional requirements table with same hierarchical structure
        Schema::create('srs_non_functional_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('srs_document_id')->constrained('srs_documents')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('srs_non_functional_requirements')->nullOnDelete();
            $table->string('requirement_id');
            $table->string('section_number');
            $table->string('title');
            $table->text('description');
            $table->enum('category', [
                'performance',
                'security',
                'reliability',
                'availability',
                'maintainability',
                'scalability',
                'usability',
                'compatibility',
                'compliance',
                'other'
            ])->default('other');
            $table->text('acceptance_criteria')->nullable();
            $table->string('measurement')->nullable(); // How to measure/verify
            $table->string('target_value')->nullable(); // Target metric
            $table->string('source')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['draft', 'review', 'approved', 'implemented', 'verified'])->default('draft');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Add additional SRS sections to main document
        Schema::table('srs_documents', function (Blueprint $table) {
            // Document metadata
            $table->string('version')->default('1.0')->after('assumptions');
            $table->enum('status', ['draft', 'review', 'approved', 'final'])->default('draft')->after('version');
            
            // Introduction section
            $table->text('purpose')->nullable()->after('description');
            $table->text('document_conventions')->nullable()->after('purpose');
            $table->text('intended_audience')->nullable()->after('document_conventions');
            $table->text('product_scope')->nullable()->after('intended_audience');
            $table->text('references')->nullable()->after('product_scope');
            
            // Overall Description
            $table->text('product_perspective')->nullable()->after('scope');
            $table->text('product_features')->nullable()->after('product_perspective');
            $table->text('user_classes')->nullable()->after('product_features');
            $table->text('operating_environment')->nullable()->after('user_classes');
            $table->text('design_constraints')->nullable()->after('operating_environment');
            $table->text('dependencies')->nullable()->after('design_constraints');
            
            // Other sections
            $table->text('external_interfaces')->nullable();
            $table->text('system_features')->nullable();
            $table->text('data_requirements')->nullable();
            $table->text('appendices')->nullable();
            $table->text('glossary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('srs_functional_requirements', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'section_number', 'acceptance_criteria', 'source', 'status']);
            $table->unique('requirement_id');
        });

        Schema::dropIfExists('srs_non_functional_requirements');

        Schema::table('srs_documents', function (Blueprint $table) {
            $table->dropColumn([
                'version',
                'status',
                'purpose',
                'document_conventions',
                'intended_audience',
                'product_scope',
                'references',
                'product_perspective',
                'product_features',
                'user_classes',
                'operating_environment',
                'design_constraints',
                'dependencies',
                'external_interfaces',
                'system_features',
                'data_requirements',
                'appendices',
                'glossary',
            ]);
        });
    }
};
