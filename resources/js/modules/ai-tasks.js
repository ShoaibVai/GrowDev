const jsonHeaders = () => ({
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
});

export async function startLayeredGeneration(projectId, payload = {}) {
    const response = await fetch(`/projects/${projectId}/ai-tasks/layered/start`, {
        method: 'POST',
        headers: jsonHeaders(),
        credentials: 'same-origin',
        body: JSON.stringify(payload),
    });

    return response.json();
}

export async function pollLayeredGeneration(projectId, runId) {
    const response = await fetch(`/projects/${projectId}/ai-tasks/layered/${runId}`, {
        headers: {'Accept': 'application/json'},
        credentials: 'same-origin',
    });

    return response.json();
}

export async function commitLayeredGeneration(projectId, runId) {
    const response = await fetch(`/projects/${projectId}/ai-tasks/layered/${runId}/commit`, {
        method: 'POST',
        headers: jsonHeaders(),
        credentials: 'same-origin',
        body: JSON.stringify({}),
    });

    return response.json();
}

export async function loadPrompt(taskId) {
    const response = await fetch(`/tasks/${taskId}/prompt`, {
        headers: {'Accept': 'application/json'},
        credentials: 'same-origin',
    });

    return response.json();
}

window.aiTasks = {
    startLayeredGeneration,
    pollLayeredGeneration,
    commitLayeredGeneration,
    loadPrompt,
};
