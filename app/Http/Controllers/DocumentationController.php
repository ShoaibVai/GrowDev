<?php

namespace App\Http\Controllers;

use App\Models\SrsDocument;
use App\Models\SddDocument;
use App\Models\SrsFunctionalRequirement;
use App\Models\SddComponent;
use App\Models\SddDiagram;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentationController extends Controller
{
    use AuthorizesRequests;
    // ===== SRS METHODS =====

    /**
     * Show all SRS documents for the user.
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
    public function createSrs(): View
    {
        return view('documentation.srs.create');
    }

    /**
     * Store new SRS document.
     */
    public function storeSrs(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_overview' => 'nullable|string',
            'scope' => 'nullable|string',
            'constraints' => 'nullable|string',
            'assumptions' => 'nullable|string',
        ]);

        $srsDocument = auth()->user()->srsDocuments()->create($validated);

        return redirect()->route('documentation.srs.edit', $srsDocument)
            ->with('success', 'SRS document created successfully.');
    }

    /**
     * Show form to edit SRS document.
     */
    public function editSrs(SrsDocument $srsDocument): View
    {
        $this->authorize('update', $srsDocument);
        $functionalRequirements = $srsDocument->functionalRequirements;
        return view('documentation.srs.edit', compact('srsDocument', 'functionalRequirements'));
    }

    /**
     * Update SRS document.
     */
    public function updateSrs(Request $request, SrsDocument $srsDocument): RedirectResponse
    {
        $this->authorize('update', $srsDocument);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_overview' => 'nullable|string',
            'scope' => 'nullable|string',
            'constraints' => 'nullable|string',
            'assumptions' => 'nullable|string',
            'functional_requirements' => 'nullable|array',
            'functional_requirements.*.requirement_id' => 'required_with:functional_requirements|string',
            'functional_requirements.*.title' => 'required_with:functional_requirements|string|max:255',
            'functional_requirements.*.description' => 'required_with:functional_requirements|string',
            'functional_requirements.*.priority' => 'required_with:functional_requirements|in:low,medium,high,critical',
            'functional_requirements.*.ux_considerations' => 'nullable|array',
        ]);

        $srsDocument->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'project_overview' => $validated['project_overview'],
            'scope' => $validated['scope'],
            'constraints' => $validated['constraints'],
            'assumptions' => $validated['assumptions'],
        ]);

        // Sync functional requirements
        if (isset($validated['functional_requirements'])) {
            $srsDocument->functionalRequirements()->delete();
            foreach ($validated['functional_requirements'] as $index => $req) {
                $srsDocument->functionalRequirements()->create([
                    'requirement_id' => $req['requirement_id'],
                    'title' => $req['title'],
                    'description' => $req['description'],
                    'priority' => $req['priority'],
                    'ux_considerations' => $req['ux_considerations'] ?? [],
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('documentation.srs.edit', $srsDocument)
            ->with('success', 'SRS document updated successfully.');
    }

    /**
     * Generate SRS PDF.
     */
    public function generateSrsPdf(SrsDocument $srsDocument)
    {
        $this->authorize('view', $srsDocument);

        $pdf = Pdf::loadView('documentation.srs.pdf', compact('srsDocument'));
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

    // ===== SDD METHODS =====

    /**
     * Show all SDD documents for the user.
     */
    public function indexSdd(Request $request): View
    {
        $query = auth()->user()->sddDocuments()->latest();
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

        $sddDocuments = $query->paginate(12)->withQueryString();
        return view('documentation.sdd.index', compact('sddDocuments'));
    }

    /**
     * Show form to create new SDD document.
     */
    public function createSdd(): View
    {
        return view('documentation.sdd.create');
    }

    /**
     * Store new SDD document.
     */
    public function storeSdd(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'design_overview' => 'nullable|string',
            'architecture_overview' => 'nullable|string',
        ]);

        $sddDocument = auth()->user()->sddDocuments()->create($validated);

        return redirect()->route('documentation.sdd.edit', $sddDocument)
            ->with('success', 'SDD document created successfully.');
    }

    /**
     * Show form to edit SDD document.
     */
    public function editSdd(SddDocument $sddDocument): View
    {
        $this->authorize('update', $sddDocument);
        $components = $sddDocument->components;
        $diagrams = $sddDocument->diagrams;
        return view('documentation.sdd.edit', compact('sddDocument', 'components', 'diagrams'));
    }

    /**
     * Update SDD document.
     */
    public function updateSdd(Request $request, SddDocument $sddDocument): RedirectResponse
    {
        $this->authorize('update', $sddDocument);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'design_overview' => 'nullable|string',
            'architecture_overview' => 'nullable|string',
            'components' => 'nullable|array',
            'components.*.component_name' => 'required_with:components|string|max:255',
            'components.*.description' => 'required_with:components|string',
            'components.*.responsibility' => 'required_with:components|string',
            'components.*.interfaces' => 'nullable|string',
            'components.*.diagram_type' => 'nullable|in:mermaid,custom',
            'diagrams' => 'nullable|array',
            'diagrams.*.diagram_name' => 'required_with:diagrams|string|max:255',
            'diagrams.*.diagram_type' => 'required_with:diagrams|string',
            'diagrams.*.diagram_content' => 'required_with:diagrams|string',
        ]);

        $sddDocument->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'design_overview' => $validated['design_overview'],
            'architecture_overview' => $validated['architecture_overview'],
        ]);

        // Sync components
        if (isset($validated['components'])) {
            $sddDocument->components()->delete();
            foreach ($validated['components'] as $index => $comp) {
                $sddDocument->components()->create([
                    'component_name' => $comp['component_name'],
                    'description' => $comp['description'],
                    'responsibility' => $comp['responsibility'],
                    'interfaces' => $comp['interfaces'] ?? null,
                    'diagram_type' => $comp['diagram_type'] ?? 'mermaid',
                    'order' => $index,
                ]);
            }
        }

        // Sync diagrams
        if (isset($validated['diagrams'])) {
            $sddDocument->diagrams()->delete();
            foreach ($validated['diagrams'] as $diag) {
                $sddDocument->diagrams()->create([
                    'diagram_name' => $diag['diagram_name'],
                    'diagram_type' => $diag['diagram_type'],
                    'diagram_content' => $diag['diagram_content'],
                ]);
            }
        }

        return redirect()->route('documentation.sdd.edit', $sddDocument)
            ->with('success', 'SDD document updated successfully.');
    }

    /**
     * Generate SDD PDF.
     */
    public function generateSddPdf(SddDocument $sddDocument)
    {
        $this->authorize('view', $sddDocument);

        $pdf = Pdf::loadView('documentation.sdd.pdf', compact('sddDocument'));
        return $pdf->download('SDD_' . $sddDocument->title . '.pdf');
    }

    /**
     * Delete SDD document.
     */
    public function destroySdd(SddDocument $sddDocument): RedirectResponse
    {
        $this->authorize('delete', $sddDocument);
        $sddDocument->delete();
        return redirect()->route('documentation.sdd.index')
            ->with('success', 'SDD document deleted successfully.');
    }

    /**
     * Convert text to Mermaid diagram (API endpoint).
     */
    public function convertTextToDiagram(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'diagram_type' => 'required|in:flowchart,sequence,class,state',
        ]);

        // This would integrate with an AI service or predefined templates
        // For now, we'll return a basic structure
        $mermaidDiagrams = [
            'flowchart' => 'flowchart TD\n    A[Start] --> B{Decision}\n    B -->|Yes| C[Process]\n    B -->|No| D[End]\n    C --> D',
            'sequence' => 'sequenceDiagram\n    participant User\n    participant System\n    User->>System: Request\n    System-->>User: Response',
            'class' => 'classDiagram\n    class MyClass {\n        +property: string\n        +method(): void\n    }',
            'state' => 'stateDiagram-v2\n    [*] --> State1\n    State1 --> State2\n    State2 --> [*]',
        ];

        return response()->json([
            'success' => true,
            'diagram_content' => $mermaidDiagrams[$validated['diagram_type']],
            'diagram_type' => $validated['diagram_type'],
        ]);
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
