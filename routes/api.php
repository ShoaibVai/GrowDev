<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\DiagramController;
use App\Http\Controllers\Api\UserSearchController;

Route::middleware(['auth:sanctum'])->group(function () {
    // ===== USER SEARCH ROUTES (FR3.2, FR8.1) =====
    Route::get('/users/search', [UserSearchController::class, 'search']);

    // ===== DOCUMENTATION ROUTES =====
    
    /**
     * Get all available templates
     */
    Route::get('/templates', [DocumentationController::class, 'getTemplates']);
    
    /**
     * Get a specific template
     */
    Route::get('/templates/{template}', [DocumentationController::class, 'getTemplate']);
    
    /**
     * List documentations for a project
     */
    Route::get('/documentations', [DocumentationController::class, 'listDocumentations']);
    
    /**
     * Store new documentation
     */
    Route::post('/documentations', [DocumentationController::class, 'storeDocumentation']);
    
    /**
     * Get specific documentation
     */
    Route::get('/documentations/{documentation}', [DocumentationController::class, 'showDocumentation']);
    
    /**
     * Update documentation
     */
    Route::put('/documentations/{documentation}', [DocumentationController::class, 'updateDocumentation']);
    
    /**
     * Delete documentation
     */
    Route::delete('/documentations/{documentation}', [DocumentationController::class, 'deleteDocumentation']);
    
    /**
     * Clone documentation
     */
    Route::post('/documentations/{documentation}/clone', [DocumentationController::class, 'cloneDocumentation']);
    
    // ===== DIAGRAM ROUTES =====
    
    /**
     * Get all diagrams for a documentation
     */
    Route::get('/documentations/{documentation}/diagrams', [DiagramController::class, 'index']);
    
    /**
     * Store new diagram
     */
    Route::post('/documentations/{documentation}/diagrams', [DiagramController::class, 'store']);
    
    /**
     * Get specific diagram
     */
    Route::get('/diagrams/{diagram}', [DiagramController::class, 'show']);
    
    /**
     * Update diagram
     */
    Route::put('/diagrams/{diagram}', [DiagramController::class, 'update']);
    
    /**
     * Delete diagram
     */
    Route::delete('/diagrams/{diagram}', [DiagramController::class, 'destroy']);
    
    /**
     * Get diagram preview
     */
    Route::get('/diagrams/{diagram}/preview', [DiagramController::class, 'preview']);
    
    /**
     * Export diagram as SVG
     */
    Route::get('/diagrams/{diagram}/export-svg', [DiagramController::class, 'exportSvg']);
    
    /**
     * Validate Mermaid syntax
     */
    Route::post('/diagrams/validate-syntax', [DiagramController::class, 'validateSyntax']);
});
