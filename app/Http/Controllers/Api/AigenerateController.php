<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AI\TaskGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AigenerateController extends Controller
{
    public function __invoke(Request $request, TaskGenerationService $aiService)
    {
        $validated = $request->validate([
            'project_context' => 'required|array',
            'project_context.project_id' => 'required|integer|exists:projects,id',
        ]);

        $projectId = $validated['project_context']['project_id'];
        $project = \App\Models\Project::findOrFail($projectId);

        Gate::authorize('view', $project);

        try {
            $result = $aiService->generateTasks(
                $project,
                $project->srsDocuments()->first()
            );

            if (!($result['success'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI generation queued but may have failed.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'run_id' => $result['run_id'] ?? null,
                'status' => $result['status'] ?? 'queued',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'AI generation failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
