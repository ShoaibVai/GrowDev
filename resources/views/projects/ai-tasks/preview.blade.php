<x-app-layout>
    @vite(['resources/js/modules/ai-tasks.js'])

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="gd-chip">P-{{ $project->id }}</span>
                <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">{{ $project->name }}</h2>
                <span class="gd-badge gd-badge-purple">AI Generation</span>
            </div>
            <a href="{{ route('projects.show', $project) }}" class="gd-btn gd-btn-ghost gd-btn-sm">
                <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Context panel --}}
        <div class="gd-card p-5" style="border-top:3px solid color-mix(in srgb, var(--color-purple) 40%, transparent)">
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <p class="gd-label">Project</p>
                    <p class="text-[13px]" style="color:var(--color-text)">{{ $project->name }}</p>
                    <p class="text-[12px] mt-1" style="color:var(--color-text-muted)">{{ Str::limit($project->description ?? 'No description', 100) }}</p>
                </div>
                <div>
                    <p class="gd-label">Requirements</p>
                    <p class="text-[13px] text-[18px] font-bold mt-1" style="font-family:var(--font-mono);color:var(--color-accent)">{{ $srsDocument?->functionalRequirements->count() ?? 0 }}</p>
                    <p class="text-[12px]" style="color:var(--color-text-muted)">functional</p>
                    <p class="text-[13px] text-[18px] font-bold mt-1" style="font-family:var(--font-mono);color:var(--color-purple)">{{ $srsDocument?->nonFunctionalRequirements->count() ?? 0 }}</p>
                    <p class="text-[12px]" style="color:var(--color-text-muted)">non-functional</p>
                </div>
                <div>
                    <p class="gd-label">Team</p>
                    <p class="text-[13px]" style="color:var(--color-text)">{{ $teamMembers->count() }} members</p>
                    <p class="text-[12px]" style="color:var(--color-text-muted)">{{ $systemRoles->count() }} roles</p>
                </div>
            </div>
        </div>

        {{-- Generation controls --}}
        <div class="gd-card p-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-[14px] font-semibold" style="color:var(--color-text)">Layered Generation</p>
                    <p class="text-[12px]" style="color:var(--color-text-muted)">Outline &rarr; Scaffolds &rarr; Task Prompts &rarr; Save</p>
                </div>
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 text-[13px] cursor-pointer" style="color:var(--color-text-muted)">
                        <input id="mockAiInput" type="checkbox" class="rounded" checked style="accent-color:var(--color-accent)">
                        Mock AI
                    </label>
                    <button id="startBtn" type="button" class="gd-btn gd-btn-primary">Generate</button>
                    <button id="commitBtn" type="button" class="hidden gd-btn" style="background:var(--color-success);color:#fff">Save Tasks</button>
                </div>
            </div>

            {{-- Layer progress stepper --}}
            <div class="mt-5 grid grid-cols-4 gap-2" id="layerSteps">
                <div data-layer="1" class="rounded-md p-3 text-center gd-card" style="background:var(--color-surface)">
                    <p class="text-[11px] font-semibold uppercase tracking-wider mb-0.5" style="color:var(--color-text-faint)">Layer 1</p>
                    <p class="text-[12px]" style="color:var(--color-text-muted)">Outline</p>
                </div>
                <div data-layer="2" class="rounded-md p-3 text-center gd-card" style="background:var(--color-surface)">
                    <p class="text-[11px] font-semibold uppercase tracking-wider mb-0.5" style="color:var(--color-text-faint)">Layer 2</p>
                    <p class="text-[12px]" style="color:var(--color-text-muted)">Scaffolds</p>
                </div>
                <div data-layer="3" class="rounded-md p-3 text-center gd-card" style="background:var(--color-surface)">
                    <p class="text-[11px] font-semibold uppercase tracking-wider mb-0.5" style="color:var(--color-text-faint)">Layer 3</p>
                    <p class="text-[12px]" style="color:var(--color-text-muted)">Prompts</p>
                </div>
                <div id="statusBox" class="rounded-md p-3 text-center gd-card" style="background:var(--color-surface)">
                    <p class="text-[11px] font-semibold uppercase tracking-wider mb-0.5" style="color:var(--color-text-faint)">Status</p>
                    <p id="statusText" class="text-[12px]" style="color:var(--color-text-muted)">Idle</p>
                </div>
            </div>
        </div>

        {{-- Preview --}}
        <div id="previewBox" class="hidden gd-card p-5">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[14px] font-semibold" style="color:var(--color-text)">Preview</p>
                <span id="previewSummary" class="text-[12px]" style="color:var(--color-text-muted)"></span>
            </div>
            <div id="previewContent" class="space-y-4"></div>
        </div>

        {{-- Error --}}
        <div id="errorBox" class="hidden gd-card p-4" style="border-left:3px solid var(--color-danger)">
            <p class="text-[13px]" style="color:var(--color-danger)"></p>
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
                showError(result.message || result.error || 'Generation failed.');
                return;
            }

            activeRunId = result.run_id;
            pollTimer = setInterval(pollGeneration, 1500);
            await pollGeneration();
        }

        async function pollGeneration() {
            if (!activeRunId) return;
            const result = await window.aiTasks.pollLayeredGeneration(projectId, activeRunId);
            if (!result.success) { showError(result.message || result.error || 'Generation failed.'); return; }
            setStatus(result.status, result.layer || 0);
            renderPreview(result.preview || {});
            if (result.status === 'ready_to_commit') {
                clearInterval(pollTimer);
                document.getElementById('commitBtn').classList.remove('hidden');
                document.getElementById('startBtn').disabled = false;
            }
            if (result.status === 'failed') { clearInterval(pollTimer); showError(result.error || 'Generation failed.'); }
        }

        async function commitGeneration() {
            if (!activeRunId) return;
            document.getElementById('commitBtn').disabled = true;
            const result = await window.aiTasks.commitLayeredGeneration(projectId, activeRunId);
            if (result.success) { window.location.href = result.redirect; return; }
            showError(result.message || result.error || 'Could not save tasks.');
        }

        function setStatus(status, layer = 0) {
            document.getElementById('statusText').textContent = status.replaceAll('_', ' ');
            document.querySelectorAll('#layerSteps [data-layer]').forEach(step => {
                const stepLayer = parseInt(step.dataset.layer, 10);
                if (stepLayer <= layer) {
                    step.style.background = 'color-mix(in srgb, var(--color-purple) 8%, var(--color-surface))';
                    step.style.borderColor = 'color-mix(in srgb, var(--color-purple) 30%, transparent)';
                } else {
                    step.style.background = 'var(--color-surface)';
                    step.style.borderColor = 'var(--color-border)';
                }
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
            document.getElementById('previewSummary').textContent = scaffolds.length+' scaffolds, '+tasks.length+' tasks';
            content.innerHTML = (conflicts.length ? `<div class="gd-card p-3 mb-3" style="border-left:3px solid var(--color-warning)"><p class="text-[12px]" style="color:var(--color-warning)">${conflicts.length} file overlap group${conflicts.length===1?'':'s'} detected.</p></div>` : '') +
                scaffolds.map(scaffold => renderScaffold(scaffold, tasks.filter(t => t.scaffold_temp_id === scaffold.temp_id))).join('');
        }

        function renderScaffold(scaffold, dependents) {
            return `<div class="gd-card p-4 mb-3"><div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3"><div>
                <div class="flex items-center gap-2"><span class="gd-badge gd-badge-purple">Scaffold</span><span class="text-[13px] font-semibold" style="color:var(--color-text)">${e(scaffold.component||'Component')}</span></div>
                <p class="mt-1 text-[12px]" style="color:var(--color-text-muted)">${e(scaffold.prompt_brief||'')}</p></div>
                <span class="text-[11px]" style="color:var(--color-text-faint);font-family:var(--font-mono)">${dependents.length} tasks</span></div>
                <div class="mt-3 flex flex-wrap gap-1">${(scaffold.predicted_files||[]).map(f=>`<code class="gd-chip text-[10px]">${e(f)}</code>`).join('')}</div>
                <div class="mt-3 space-y-1.5">${dependents.map(task=>`<div class="rounded-md p-2.5" style="background:var(--color-base)"><div class="flex items-center justify-between gap-3">
                <span class="text-[13px] font-medium" style="color:var(--color-text)">${e(task.title)}</span>
                <span class="text-[10px]" style="font-family:var(--font-mono);color:var(--color-text-faint)">${e(task.scaffold_temp_id||'')}</span></div>
                <p class="mt-1 text-[12px]" style="color:var(--color-text-muted)">${e(task.prompt_brief||'')}</p></div>`).join('')}</div></div>`;
        }

        function resetState() { clearInterval(pollTimer); document.getElementById('errorBox').classList.add('hidden'); document.getElementById('commitBtn').classList.add('hidden'); document.getElementById('previewBox').classList.add('hidden'); document.getElementById('previewContent').innerHTML = ''; }
        function showError(msg) { clearInterval(pollTimer); document.getElementById('startBtn').disabled = false; const box = document.getElementById('errorBox'); box.querySelector('p').textContent = msg; box.classList.remove('hidden'); }
        function e(t) { const d = document.createElement('div'); d.textContent = t||''; return d.innerHTML; }
    </script>
    @endpush
</x-app-layout>
