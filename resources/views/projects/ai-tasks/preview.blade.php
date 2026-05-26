<x-app-layout>
    @vite(['resources/js/modules/ai-tasks.js'])

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('AI Task Generation') }} - {{ $project->name }}
            </h2>
            <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-800">
                Back to Project
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Project</h3>
                            <p class="mt-2 font-medium text-gray-900">{{ $project->name }}</p>
                            <p class="mt-1 text-sm text-gray-600">{{ $project->description ?? 'No description provided.' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Requirements</h3>
                            <p class="mt-2 text-sm text-gray-700">{{ $srsDocument?->functionalRequirements->count() ?? 0 }} functional</p>
                            <p class="text-sm text-gray-700">{{ $srsDocument?->nonFunctionalRequirements->count() ?? 0 }} non-functional</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Team</h3>
                            <p class="mt-2 text-sm text-gray-700">{{ $teamMembers->count() }} members available</p>
                            <p class="text-sm text-gray-700">{{ $systemRoles->count() }} roles known</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Layered generation</h3>
                            <p class="text-sm text-gray-600">Server-side jobs generate the outline, scaffold prompts, and task prompts before saving tasks.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                                <input id="mockAiInput" type="checkbox" class="rounded border-gray-300" checked>
                                Mock AI
                            </label>
                            <button id="startBtn" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Generate
                            </button>
                            <button id="commitBtn" type="button" class="hidden px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Save Tasks
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-3" id="layerSteps">
                        <div data-layer="1" class="rounded border border-gray-200 bg-gray-50 p-3 text-sm">
                            <span class="font-medium">Layer 1</span>
                            <p class="text-gray-500">Outline</p>
                        </div>
                        <div data-layer="2" class="rounded border border-gray-200 bg-gray-50 p-3 text-sm">
                            <span class="font-medium">Layer 2</span>
                            <p class="text-gray-500">Scaffolds</p>
                        </div>
                        <div data-layer="3" class="rounded border border-gray-200 bg-gray-50 p-3 text-sm">
                            <span class="font-medium">Layer 3</span>
                            <p class="text-gray-500">Task prompts</p>
                        </div>
                        <div id="statusBox" class="rounded border border-gray-200 bg-gray-50 p-3 text-sm">
                            <span class="font-medium">Status</span>
                            <p id="statusText" class="text-gray-500">Idle</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="previewBox" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Preview</h3>
                        <span id="previewSummary" class="text-sm text-gray-500"></span>
                    </div>
                    <div id="previewContent" class="space-y-6"></div>
                </div>
            </div>

            <div id="errorBox" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 text-red-700"></div>
        </div>
    </div>

    @push('scripts')
    <script>
        const projectId = @json($project->id);
        let activeRunId = null;
        let pollTimer = null;

        document.getElementById('startBtn').addEventListener('click', startGeneration);
        document.getElementById('commitBtn').addEventListener('click', commitGeneration);

        async function startGeneration() {
            resetState();
            setStatus('queued');
            document.getElementById('startBtn').disabled = true;

            const result = await window.aiTasks.startLayeredGeneration(projectId, {
                mock_ai: document.getElementById('mockAiInput').checked,
            });

            if (!result.success) {
                showError(result.message || result.error || 'Generation failed to start.');
                return;
            }

            activeRunId = result.run_id;
            pollTimer = setInterval(pollGeneration, 1500);
            await pollGeneration();
        }

        async function pollGeneration() {
            if (!activeRunId) return;

            const result = await window.aiTasks.pollLayeredGeneration(projectId, activeRunId);

            if (!result.success) {
                showError(result.message || result.error || 'Generation failed.');
                return;
            }

            setStatus(result.status, result.layer || 0);
            renderPreview(result.preview || {});

            if (result.status === 'ready_to_commit') {
                clearInterval(pollTimer);
                document.getElementById('commitBtn').classList.remove('hidden');
                document.getElementById('startBtn').disabled = false;
            }

            if (result.status === 'failed') {
                clearInterval(pollTimer);
                showError(result.error || 'Generation failed.');
            }
        }

        async function commitGeneration() {
            if (!activeRunId) return;

            document.getElementById('commitBtn').disabled = true;
            const result = await window.aiTasks.commitLayeredGeneration(projectId, activeRunId);

            if (result.success) {
                window.location.href = result.redirect;
                return;
            }

            showError(result.message || result.error || 'Could not save generated tasks.');
        }

        function setStatus(status, layer = 0) {
            document.getElementById('statusText').textContent = status.replaceAll('_', ' ');
            document.querySelectorAll('#layerSteps [data-layer]').forEach(step => {
                const stepLayer = parseInt(step.dataset.layer, 10);
                step.className = stepLayer <= layer
                    ? 'rounded border border-indigo-200 bg-indigo-50 p-3 text-sm'
                    : 'rounded border border-gray-200 bg-gray-50 p-3 text-sm';
            });
        }

        function renderPreview(preview) {
            const scaffolds = preview.scaffolds || [];
            const tasks = preview.tasks || [];
            const conflicts = preview.conflicts || [];
            const box = document.getElementById('previewBox');
            const content = document.getElementById('previewContent');

            if (scaffolds.length === 0 && tasks.length === 0) return;

            box.classList.remove('hidden');
            document.getElementById('previewSummary').textContent = `${scaffolds.length} scaffolds, ${tasks.length} tasks`;

            content.innerHTML = `
                ${conflicts.length ? `<div class="rounded border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    ${conflicts.length} file overlap group${conflicts.length === 1 ? '' : 's'} detected. Dependent tasks will wait for their scaffold.
                </div>` : ''}
                ${scaffolds.map(scaffold => renderScaffold(scaffold, tasks.filter(task => task.scaffold_temp_id === scaffold.temp_id))).join('')}
            `;
        }

        function renderScaffold(scaffold, dependents) {
            return `
                <section class="border border-gray-200 rounded-lg p-4">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-xs font-semibold rounded bg-indigo-100 text-indigo-700">Scaffold</span>
                                <h4 class="font-semibold text-gray-900">${escapeHtml(scaffold.component || 'Component')}</h4>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">${escapeHtml(scaffold.prompt_brief || '')}</p>
                        </div>
                        <span class="text-xs text-gray-500">${dependents.length} dependent tasks</span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        ${(scaffold.predicted_files || []).map(file => `<span class="px-2 py-1 rounded bg-gray-100 text-xs text-gray-600">${escapeHtml(file)}</span>`).join('')}
                    </div>
                    <div class="mt-4 space-y-2">
                        ${dependents.map(task => `
                            <div class="rounded bg-gray-50 p-3">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-medium text-sm text-gray-900">${escapeHtml(task.title)}</span>
                                    <span class="text-xs text-gray-500">depends on ${escapeHtml(task.scaffold_temp_id || '')}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">${escapeHtml(task.prompt_brief || '')}</p>
                            </div>
                        `).join('')}
                    </div>
                </section>
            `;
        }

        function resetState() {
            clearInterval(pollTimer);
            document.getElementById('errorBox').classList.add('hidden');
            document.getElementById('commitBtn').classList.add('hidden');
            document.getElementById('previewBox').classList.add('hidden');
            document.getElementById('previewContent').innerHTML = '';
        }

        function showError(message) {
            clearInterval(pollTimer);
            document.getElementById('startBtn').disabled = false;
            const box = document.getElementById('errorBox');
            box.textContent = message;
            box.classList.remove('hidden');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }
    </script>
    @endpush
</x-app-layout>
