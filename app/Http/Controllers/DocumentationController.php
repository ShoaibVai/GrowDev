<?php

namespace App\Http\Controllers;

use App\Models\SrsDocument;
use App\Models\SrsFunctionalRequirement;
use App\Models\SrsNonFunctionalRequirement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

/**
 * DocumentationController
 * 
 * Handles all documentation-related operations including:
 * - SRS (Software Requirements Specification) document management
 * - PDF generation and export
 * - Requirements management (Functional and Non-Functional)
 * - Role mapping for requirements
 * 
 * @package App\Http\Controllers
 */
class DocumentationController extends Controller
{
    use AuthorizesRequests;
    // ===== SRS METHODS =====

    /**
     * Show all SRS documents for the authenticated user.
     * 
     * Supports filtering by search query and team assignment.
     * Supports multiple sorting options (latest, oldest, alphabetical).
     *
     * @param Request $request Query parameters for filtering and sorting
     * @return View SRS documents list view
     */
    public function indexSrs(Request $request): View
    {
        $query = auth()->user()->srsDocuments()->latest();
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('sort')) {
            switch ($request->get('sort')) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'name_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('title', 'desc');
                    break;
                default:
                    // latest is default
                    break;
            }
        }

        $srsDocuments = $query->paginate(12)->withQueryString();
        return view('documentation.srs.index', compact('srsDocuments'));
    }

    /**
     * Show form to create new SRS document.
     */
    public function createSrs(Request $request): View
    {
        $projects = auth()->user()->projects()->latest()->get();
        $selectedProjectId = $request->integer('project_id');
        $nfrCategories = SrsNonFunctionalRequirement::CATEGORIES;
        
        // Determine roles available for the selected project (if any)
        $roles = collect();
        if ($selectedProjectId) {
            $project = auth()->user()->projects()->find($selectedProjectId);
            if ($project && $project->team) {
                $roles = $project->team->roles()->get();
            }
        }

        return view('documentation.srs.create', compact('projects', 'selectedProjectId', 'nfrCategories', 'roles'));
    }

    /**
     * Store new SRS document.
     */
    public function storeSrs(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')->where(fn ($query) => $query->where('user_id', auth()->id())),
            ],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purpose' => 'nullable|string',
            'document_conventions' => 'nullable|string',
            'intended_audience' => 'nullable|string',
            'product_scope' => 'nullable|string',
            'references' => 'nullable|string',
            'project_overview' => 'nullable|string',
            'scope' => 'nullable|string',
            'product_perspective' => 'nullable|string',
            'product_features' => 'nullable|string',
            'user_classes' => 'nullable|string',
            'operating_environment' => 'nullable|string',
            'design_constraints' => 'nullable|string',
            'constraints' => 'nullable|string',
            'assumptions' => 'nullable|string',
            'dependencies' => 'nullable|string',
            'external_interfaces' => 'nullable|string',
            'data_requirements' => 'nullable|string',
            'glossary' => 'nullable|string',
            'appendices' => 'nullable|string',
            'version' => 'nullable|string',
            'status' => 'nullable|in:draft,review,approved,final',
            'functional_requirements' => 'nullable|array',
            'functional_requirements.*.requirement_id' => 'required_with:functional_requirements|string',
            'functional_requirements.*.section_number' => 'required_with:functional_requirements|string',
            'functional_requirements.*.title' => 'required_with:functional_requirements|string|max:255',
            'functional_requirements.*.description' => 'required_with:functional_requirements|string',
            'functional_requirements.*.acceptance_criteria' => 'nullable|string',
            'functional_requirements.*.source' => 'nullable|string',
            'functional_requirements.*.priority' => 'required_with:functional_requirements|in:low,medium,high,critical',
            'functional_requirements.*.status' => 'nullable|in:draft,review,approved,implemented,verified',
            'functional_requirements.*.ux_considerations' => 'nullable|array',
            'functional_requirements.*.parent_section' => 'nullable|string',
            'functional_requirements.*.roles' => 'nullable|array',
            'functional_requirements.*.roles.*' => 'nullable|integer|exists:roles,id',
            'non_functional_requirements' => 'nullable|array',
            'non_functional_requirements.*.requirement_id' => 'required_with:non_functional_requirements|string',
            'non_functional_requirements.*.section_number' => 'required_with:non_functional_requirements|string',
            'non_functional_requirements.*.title' => 'required_with:non_functional_requirements|string|max:255',
            'non_functional_requirements.*.description' => 'required_with:non_functional_requirements|string',
            'non_functional_requirements.*.category' => 'required_with:non_functional_requirements|in:performance,security,reliability,availability,maintainability,scalability,usability,compatibility,compliance,other',
            'non_functional_requirements.*.acceptance_criteria' => 'nullable|string',
            'non_functional_requirements.*.measurement' => 'nullable|string',
            'non_functional_requirements.*.target_value' => 'nullable|string',
            'non_functional_requirements.*.source' => 'nullable|string',
            'non_functional_requirements.*.priority' => 'required_with:non_functional_requirements|in:low,medium,high,critical',
            'non_functional_requirements.*.status' => 'nullable|in:draft,review,approved,implemented,verified',
            'non_functional_requirements.*.parent_section' => 'nullable|string',
            'non_functional_requirements.*.roles' => 'nullable|array',
            'non_functional_requirements.*.roles.*' => 'nullable|integer|exists:roles,id',
        ]);

        $srsDocument = auth()->user()->srsDocuments()->create([
            'project_id' => $validated['project_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'purpose' => $validated['purpose'] ?? null,
            'document_conventions' => $validated['document_conventions'] ?? null,
            'intended_audience' => $validated['intended_audience'] ?? null,
            'product_scope' => $validated['product_scope'] ?? null,
            'references' => $validated['references'] ?? null,
            'project_overview' => $validated['project_overview'] ?? null,
            'scope' => $validated['scope'] ?? null,
            'product_perspective' => $validated['product_perspective'] ?? null,
            'product_features' => $validated['product_features'] ?? null,
            'user_classes' => $validated['user_classes'] ?? null,
            'operating_environment' => $validated['operating_environment'] ?? null,
            'design_constraints' => $validated['design_constraints'] ?? null,
            'constraints' => $validated['constraints'] ?? null,
            'assumptions' => $validated['assumptions'] ?? null,
            'dependencies' => $validated['dependencies'] ?? null,
            'external_interfaces' => $validated['external_interfaces'] ?? null,
            'data_requirements' => $validated['data_requirements'] ?? null,
            'glossary' => $validated['glossary'] ?? null,
            'appendices' => $validated['appendices'] ?? null,
            'version' => $validated['version'] ?? '1.0',
            'status' => $validated['status'] ?? 'draft',
        ]);

        // Sync functional requirements with hierarchical structure
        $this->syncHierarchicalRequirements(
            $srsDocument, 
            $validated['functional_requirements'] ?? [], 
            'functional'
        );

        // Sync non-functional requirements with hierarchical structure
        $this->syncHierarchicalRequirements(
            $srsDocument, 
            $validated['non_functional_requirements'] ?? [], 
            'non_functional'
        );

        // Notify team members of new SRS if they allow SRS update notifications
        if ($srsDocument->project && $srsDocument->project->team) {
            $members = $srsDocument->project->team->members()->get();
            foreach ($members as $m) {
                if ($m->id === auth()->id()) continue; // skip actor
                $pref = $m->notificationPreference;
                $allowEmail = $pref ? (bool) $pref->email_on_srs_update : true;
                if ($allowEmail) {
                    $m->notify(new \App\Notifications\SrsUpdated($srsDocument));
                } else {
                    \App\Models\NotificationEvent::create([
                        'user_id' => $m->id,
                        'event_type' => 'srs_updated',
                        'payload' => ['srs_id' => $srsDocument->id, 'project_id' => $srsDocument->project_id],
                        'sent' => false,
                    ]);
                }
            }
        }

        return redirect()->route('documentation.srs.edit', $srsDocument)
            ->with('success', 'SRS document created successfully. You can now add requirements.');
    }

    /**
     * Show form to edit SRS document.
     */
    public function editSrs(SrsDocument $srsDocument): View
    {
        $this->authorize('update', $srsDocument);
        $functionalRequirements = $srsDocument->functionalRequirements()->with('children')->get();
        $nonFunctionalRequirements = $srsDocument->nonFunctionalRequirements()->with('children')->get();
        $nfrCategories = SrsNonFunctionalRequirement::CATEGORIES;
        $projects = auth()->user()->projects()->latest()->get();
        // Determine roles available for the related project (if any)
        $roles = collect();
        if ($srsDocument->project_id && $srsDocument->project && $srsDocument->project->team) {
            $roles = $srsDocument->project->team->roles()->get();
        }
        return view('documentation.srs.edit', compact('srsDocument', 'functionalRequirements', 'nonFunctionalRequirements', 'nfrCategories', 'projects', 'roles'));
    }

    /**
     * Update SRS document.
     */
    public function updateSrs(Request $request, SrsDocument $srsDocument): RedirectResponse
    {
        $this->authorize('update', $srsDocument);

        $validated = $request->validate([
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')->where(fn ($query) => $query->where('user_id', auth()->id())),
            ],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purpose' => 'nullable|string',
            'document_conventions' => 'nullable|string',
            'intended_audience' => 'nullable|string',
            'product_scope' => 'nullable|string',
            'references' => 'nullable|string',
            'project_overview' => 'nullable|string',
            'scope' => 'nullable|string',
            'product_perspective' => 'nullable|string',
            'product_features' => 'nullable|string',
            'user_classes' => 'nullable|string',
            'operating_environment' => 'nullable|string',
            'design_constraints' => 'nullable|string',
            'constraints' => 'nullable|string',
            'assumptions' => 'nullable|string',
            'dependencies' => 'nullable|string',
            'external_interfaces' => 'nullable|string',
            'system_features' => 'nullable|string',
            'data_requirements' => 'nullable|string',
            'appendices' => 'nullable|string',
            'glossary' => 'nullable|string',
            'version' => 'nullable|string',
            'status' => 'nullable|in:draft,review,approved,final',
            'functional_requirements' => 'nullable|array',
            'functional_requirements.*.requirement_id' => 'required_with:functional_requirements|string',
            'functional_requirements.*.section_number' => 'required_with:functional_requirements|string',
            'functional_requirements.*.title' => 'required_with:functional_requirements|string|max:255',
            'functional_requirements.*.description' => 'required_with:functional_requirements|string',
            'functional_requirements.*.acceptance_criteria' => 'nullable|string',
            'functional_requirements.*.source' => 'nullable|string',
            'functional_requirements.*.priority' => 'required_with:functional_requirements|in:low,medium,high,critical',
            'functional_requirements.*.status' => 'nullable|in:draft,review,approved,implemented,verified',
            'functional_requirements.*.ux_considerations' => 'nullable|array',
            'functional_requirements.*.parent_section' => 'nullable|string',
            'functional_requirements.*.roles' => 'nullable|array',
            'functional_requirements.*.roles.*' => 'nullable|integer|exists:roles,id',
            'non_functional_requirements' => 'nullable|array',
            'non_functional_requirements.*.requirement_id' => 'required_with:non_functional_requirements|string',
            'non_functional_requirements.*.section_number' => 'required_with:non_functional_requirements|string',
            'non_functional_requirements.*.title' => 'required_with:non_functional_requirements|string|max:255',
            'non_functional_requirements.*.description' => 'required_with:non_functional_requirements|string',
            'non_functional_requirements.*.category' => 'required_with:non_functional_requirements|in:performance,security,reliability,availability,maintainability,scalability,usability,compatibility,compliance,other',
            'non_functional_requirements.*.acceptance_criteria' => 'nullable|string',
            'non_functional_requirements.*.measurement' => 'nullable|string',
            'non_functional_requirements.*.target_value' => 'nullable|string',
            'non_functional_requirements.*.source' => 'nullable|string',
            'non_functional_requirements.*.priority' => 'required_with:non_functional_requirements|in:low,medium,high,critical',
            'non_functional_requirements.*.status' => 'nullable|in:draft,review,approved,implemented,verified',
            'non_functional_requirements.*.parent_section' => 'nullable|string',
            'non_functional_requirements.*.roles' => 'nullable|array',
            'non_functional_requirements.*.roles.*' => 'nullable|integer|exists:roles,id',
        ]);

        // capture existing requirement state so we can compute changed sections
        $existingFunctional = $srsDocument->functionalRequirements()->get()->keyBy('section_number')->toArray();
        $existingNonFunctional = $srsDocument->nonFunctionalRequirements()->get()->keyBy('section_number')->toArray();

        $srsDocument->update([
            'project_id' => $validated['project_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'purpose' => $validated['purpose'] ?? null,
            'document_conventions' => $validated['document_conventions'] ?? null,
            'intended_audience' => $validated['intended_audience'] ?? null,
            'product_scope' => $validated['product_scope'] ?? null,
            'references' => $validated['references'] ?? null,
            'project_overview' => $validated['project_overview'] ?? null,
            'scope' => $validated['scope'] ?? null,
            'product_perspective' => $validated['product_perspective'] ?? null,
            'product_features' => $validated['product_features'] ?? null,
            'user_classes' => $validated['user_classes'] ?? null,
            'operating_environment' => $validated['operating_environment'] ?? null,
            'design_constraints' => $validated['design_constraints'] ?? null,
            'constraints' => $validated['constraints'] ?? null,
            'assumptions' => $validated['assumptions'] ?? null,
            'dependencies' => $validated['dependencies'] ?? null,
            'external_interfaces' => $validated['external_interfaces'] ?? null,
            'system_features' => $validated['system_features'] ?? null,
            'data_requirements' => $validated['data_requirements'] ?? null,
            'appendices' => $validated['appendices'] ?? null,
            'glossary' => $validated['glossary'] ?? null,
            'version' => $validated['version'] ?? '1.0',
            'status' => $validated['status'] ?? 'draft',
        ]);

        // Sync functional requirements with hierarchical structure
        $this->syncHierarchicalRequirements(
            $srsDocument, 
            $validated['functional_requirements'] ?? [], 
            'functional'
        );

        // Sync non-functional requirements with hierarchical structure
        $this->syncHierarchicalRequirements(
            $srsDocument, 
            $validated['non_functional_requirements'] ?? [], 
            'non_functional'
        );
        // compute changed sections by comparing existing vs validated
        $changedSections = [];
        foreach (($validated['functional_requirements'] ?? []) as $req) {
            $section = $req['section_number'];
            $existing = $existingFunctional[$section] ?? null;
            if (!$existing) {
                $changedSections['functional'][] = ['section' => $section, 'type' => 'added', 'title' => $req['title']];
            } else {
                if ($existing['title'] !== $req['title'] || $existing['description'] !== $req['description']) {
                    $changedSections['functional'][] = ['section' => $section, 'type' => 'modified', 'old_title' => $existing['title'], 'new_title' => $req['title']];
                }
            }
        }
        foreach (($validated['non_functional_requirements'] ?? []) as $req) {
            $section = $req['section_number'];
            $existing = $existingNonFunctional[$section] ?? null;
            if (!$existing) {
                $changedSections['non_functional'][] = ['section' => $section, 'type' => 'added', 'title' => $req['title']];
            } else {
                if ($existing['title'] !== $req['title'] || $existing['description'] !== $req['description']) {
                    $changedSections['non_functional'][] = ['section' => $section, 'type' => 'modified', 'old_title' => $existing['title'], 'new_title' => $req['title']];
                }
            }
        }

        // Notify team members on update WITH changed section info
        if ($srsDocument->project && $srsDocument->project->team) {
            $members = $srsDocument->project->team->members()->get();
            foreach ($members as $m) {
                if ($m->id === auth()->id()) continue;
                $pref = $m->notificationPreference;
                $allowEmail = $pref ? (bool) $pref->email_on_srs_update : true;
                $payload = ['srs_id' => $srsDocument->id, 'project_id' => $srsDocument->project_id, 'changed_sections' => $changedSections];
                if ($allowEmail) {
                    $m->notify(new \App\Notifications\SrsUpdated($srsDocument, $changedSections));
                } else {
                    \App\Models\NotificationEvent::create([
                        'user_id' => $m->id,
                        'event_type' => 'srs_updated',
                        'payload' => $payload,
                        'sent' => false,
                    ]);
                }
            }
        }

        // Also notify team members on update
        if ($srsDocument->project && $srsDocument->project->team) {
            $members = $srsDocument->project->team->members()->get();
            foreach ($members as $m) {
                if ($m->id === auth()->id()) continue;
                $pref = $m->notificationPreference;
                $allowEmail = $pref ? (bool) $pref->email_on_srs_update : true;
                if ($allowEmail) {
                    $m->notify(new \App\Notifications\SrsUpdated($srsDocument));
                } else {
                    \App\Models\NotificationEvent::create([
                        'user_id' => $m->id,
                        'event_type' => 'srs_updated',
                        'payload' => ['srs_id' => $srsDocument->id, 'project_id' => $srsDocument->project_id],
                        'sent' => false,
                    ]);
                }
            }
        }

        return redirect()->route('documentation.srs.edit', $srsDocument)
            ->with('success', 'SRS document updated successfully.');
    }

    /**
     * Sync hierarchical requirements (both functional and non-functional).
     */
    private function syncHierarchicalRequirements(SrsDocument $srsDocument, array $requirements, string $type): void
    {
        if ($type === 'functional') {
            // Delete any existing role mappings for current functional requirements
            $existingIds = $srsDocument->functionalRequirements()->pluck('id')->toArray();
            if (!empty($existingIds)) {
                \App\Models\RoleRequirementMapping::where('requirement_type', SrsFunctionalRequirement::class)
                    ->whereIn('requirement_id', $existingIds)
                    ->delete();
            }
            $srsDocument->functionalRequirements()->delete();
            $relationName = 'functionalRequirements';
        } else {
            $existingIds = $srsDocument->nonFunctionalRequirements()->pluck('id')->toArray();
            if (!empty($existingIds)) {
                \App\Models\RoleRequirementMapping::where('requirement_type', SrsNonFunctionalRequirement::class)
                    ->whereIn('requirement_id', $existingIds)
                    ->delete();
            }
            $srsDocument->nonFunctionalRequirements()->delete();
            $relationName = 'nonFunctionalRequirements';
        }

        if (empty($requirements)) {
            return;
        }

        // First pass: Create all requirements without parent references
        $createdRequirements = [];
        foreach ($requirements as $index => $req) {
            $data = [
                'requirement_id' => $req['requirement_id'],
                'section_number' => $req['section_number'],
                'title' => $req['title'],
                'description' => $req['description'],
                'priority' => $req['priority'],
                'status' => $req['status'] ?? 'draft',
                'order' => $index,
            ];

            if ($type === 'functional') {
                $data['acceptance_criteria'] = $req['acceptance_criteria'] ?? null;
                $data['source'] = $req['source'] ?? null;
                $data['ux_considerations'] = $req['ux_considerations'] ?? [];
            } else {
                $data['category'] = $req['category'];
                $data['acceptance_criteria'] = $req['acceptance_criteria'] ?? null;
                $data['measurement'] = $req['measurement'] ?? null;
                $data['target_value'] = $req['target_value'] ?? null;
                $data['source'] = $req['source'] ?? null;
            }

            $created = $srsDocument->$relationName()->create($data);
            // If there are role mappings provided in the request, create them
            if (!empty($req['roles']) && is_array($req['roles'])) {
                foreach ($req['roles'] as $roleId) {
                    $created->roleMappings()->create(['role_id' => $roleId]);
                }
            }
            $createdRequirements[$req['section_number']] = $created;
        }

        // Second pass: Update parent references based on section numbers
        foreach ($requirements as $req) {
            $sectionNumber = $req['section_number'];
            $parentSection = $req['parent_section'] ?? null;
            
            // If no explicit parent, try to derive from section number (e.g., 1.2.1 -> 1.2)
            if (!$parentSection && substr_count($sectionNumber, '.') > 0) {
                $parts = explode('.', $sectionNumber);
                array_pop($parts);
                $parentSection = implode('.', $parts);
            }

            if ($parentSection && isset($createdRequirements[$parentSection])) {
                $createdRequirements[$sectionNumber]->update([
                    'parent_id' => $createdRequirements[$parentSection]->id
                ]);
            }
        }
    }

    /**
     * Generate and download SRS document as PDF.
     * 
     * This method:
     * 1. Authorizes the user to view the SRS document (via policy)
     * 2. Renders the SRS data using the PDF view template
     * 3. Generates a PDF file using DomPDF
     * 4. Triggers browser download with SRS title as filename
     *
     * @param SrsDocument $srsDocument The SRS document to export
     * @return \Illuminate\Http\Response PDF file download response
     * @throws \Illuminate\Auth\Access\AuthorizationException If user cannot view the document
     */
    public function generateSrsPdf(SrsDocument $srsDocument)
    {
        // Verify user authorization via SrsDocumentPolicy
        // Ensures only the document owner can download it
        $this->authorize('view', $srsDocument);

        // Load the PDF view template with SRS data
        $pdf = Pdf::loadView('documentation.srs.pdf', compact('srsDocument'));
        
        // Return downloadable PDF with formatted filename
        return $pdf->download('SRS_' . $srsDocument->title . '.pdf');
    }

    /**
     * Delete SRS document.
     */
    public function destroySrs(SrsDocument $srsDocument): RedirectResponse
    {
        $this->authorize('delete', $srsDocument);
        $srsDocument->delete();
        return redirect()->route('documentation.srs.index')
            ->with('success', 'SRS document deleted successfully.');
    }

    // ===== TEMPLATE-BASED DOCUMENTATION METHODS =====

    /**
     * Get all available documentation templates.
     */
    public function getTemplates()
    {
        $templates = \App\Models\DocumentationTemplate::all();
        return response()->json([
            'success' => true,
            'data' => $templates,
        ]);
    }

    /**
     * Get a specific template structure.
     */
    public function getTemplate(\App\Models\DocumentationTemplate $template)
    {
        return response()->json([
            'success' => true,
            'data' => $template,
        ]);
    }

    /**
     * Display a listing of documentations for a project.
     */
    public function listDocumentations(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'status' => 'nullable|in:draft,review,approved',
            'template_id' => 'nullable|exists:documentation_templates,id',
        ]);

        $query = \App\Models\Documentation::where('project_id', $validated['project_id'])
            ->with(['template', 'creator', 'diagrams']);

        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (isset($validated['template_id'])) {
            $query->where('template_id', $validated['template_id']);
        }

        $documentations = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $documentations,
        ]);
    }

    /**
     * Store a newly created documentation using templates.
     */
    public function storeDocumentation(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'template_id' => 'required|exists:documentation_templates,id',
            'title' => 'required|string|max:255',
            'content' => 'required|array',
        ]);

        $documentation = \App\Models\Documentation::create([
            'project_id' => $validated['project_id'],
            'template_id' => $validated['template_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'version' => 1,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Documentation created successfully.',
            'data' => $documentation->load(['template', 'creator']),
        ], 201);
    }

    /**
     * Display the specified documentation.
     */
    public function showDocumentation(\App\Models\Documentation $documentation)
    {
        $documentation->load(['template', 'creator', 'diagrams.creator']);

        return response()->json([
            'success' => true,
            'data' => $documentation,
        ]);
    }

    /**
     * Update the specified documentation.
     */
    public function updateDocumentation(Request $request, \App\Models\Documentation $documentation)
    {
        $this->authorize('update', $documentation);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|array',
            'status' => 'sometimes|in:draft,review,approved',
        ]);

        if (isset($validated['content'])) {
            $documentation->incrementVersion();
        }

        $documentation->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Documentation updated successfully.',
            'data' => $documentation->load('template', 'creator'),
        ]);
    }

    /**
     * Remove the specified documentation.
     */
    public function deleteDocumentation(\App\Models\Documentation $documentation)
    {
        $this->authorize('delete', $documentation);

        $documentation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Documentation deleted successfully.',
        ]);
    }

    /**
     * Clone a documentation.
     */
    public function cloneDocumentation(\App\Models\Documentation $documentation, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'increment_version' => 'boolean',
        ]);

        $newVersion = $validated['increment_version'] ?? true;
        $newDocumentation = $documentation->replicate();
        $newDocumentation->title = $validated['title'];
        $newDocumentation->version = $newVersion ? $documentation->version + 1 : 1;
        $newDocumentation->status = 'draft';
        $newDocumentation->created_by = auth()->id();
        $newDocumentation->save();

        return response()->json([
            'success' => true,
            'message' => 'Documentation cloned successfully.',
            'data' => $newDocumentation->load('template', 'creator'),
        ], 201);
    }
}
