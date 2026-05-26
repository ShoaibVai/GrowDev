<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskPromptController extends Controller
{
    public function show(Request $request, Task $task)
    {
        $this->authorizeTaskAccess($request, $task);

        $task->loadMissing(['scaffoldTask:id,title,component,predicted_files,interface_contracts,status,scaffold_merged_at']);

        return response()->json([
            'task_id' => $task->id,
            'component' => $task->component,
            'is_scaffold' => $task->is_scaffold,
            'scaffold_task_id' => $task->scaffold_task_id,
            'scaffold' => $task->scaffoldTask ? [
                'id' => $task->scaffoldTask->id,
                'title' => $task->scaffoldTask->title,
                'component' => $task->scaffoldTask->component,
                'is_complete' => $task->scaffoldTask->isScaffoldComplete(),
                'predicted_files' => $task->scaffoldTask->predicted_files ?? [],
                'interface_contracts' => $task->scaffoldTask->interface_contracts ?? [],
            ] : null,
            'predicted_files' => $task->predicted_files ?? [],
            'prompt_brief' => $task->prompt_brief,
            'prompt_section' => $task->prompt_section,
            'interface_contracts' => $task->interface_contracts ?? [],
            'prompt_payload' => $task->prompt_payload ?? [],
        ]);
    }

    private function authorizeTaskAccess(Request $request, Task $task): void
    {
        $user = $request->user();
        $task->loadMissing('project.team.members');
        $isTeamMember = $task->project->team && $task->project->team->members->contains('id', $user->id);

        abort_unless($task->isOwnedBy($user) || $task->isAssignedTo($user) || $isTeamMember, 403);
    }
}
