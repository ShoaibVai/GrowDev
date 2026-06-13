<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <span class="gd-chip">P-{{ $project->id }}</span>
                <h2 class="text-[18px] font-semibold truncate" style="color:var(--color-text)">{{ $project->name }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('projects.board', $project) }}" class="gd-btn gd-btn-secondary gd-btn-sm">Kanban</a>
                <a href="{{ route('projects.edit', $project) }}" class="gd-btn gd-btn-secondary gd-btn-sm">Edit</a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="gd-btn gd-btn-danger gd-btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Project Info Card --}}
        <div class="gd-card p-5">
            <div class="flex flex-wrap items-center gap-6 text-[13px]">
                <div>
                    <span class="gd-label">Status</span>
                    @php $pStatus = match($project->status) { 'active' => 'in-progress', 'completed' => 'done', 'on_hold' => 'todo', default => 'todo' }; @endphp
                    <span class="gd-badge gd-badge-{{ $pStatus }}">{{ ucfirst($project->status) }}</span>
                </div>
                <div>
                    <span class="gd-label">Type</span>
                    <span style="color:var(--color-text);font-family:var(--font-mono)">{{ ucfirst($project->type) ?? 'Solo' }}</span>
                </div>
                @if($project->team)
                <div>
                    <span class="gd-label">Team</span>
                    <a href="{{ route('teams.show', $project->team) }}" class="hover:underline" style="color:var(--color-accent)">{{ $project->team->name }}</a>
                </div>
                @endif
                @if($project->start_date)
                <div>
                    <span class="gd-label">Timeline</span>
                    <span style="color:var(--color-text);font-family:var(--font-mono)">{{ $project->start_date->format('M d, Y') }} &mdash; {{ $project->end_date?->format('M d, Y') ?? 'Ongoing' }}</span>
                </div>
                @endif
            </div>
            @if($project->description)
                <p class="mt-4 text-[13px]" style="color:var(--color-text-muted)">{{ $project->description }}</p>
            @endif
        </div>

        {{-- Requirements Checklist --}}
        @if($srsDocument)
        <div class="gd-card p-0 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid var(--color-border)">
                <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Requirements Checklist</p>
                <a href="{{ route('documentation.srs.edit', $srsDocument) }}" class="text-[12px] hover:underline" style="color:var(--color-accent)">Edit SRS</a>
            </div>
            <div class="p-5">
                @php
                    $reqStatuses = [
                        'listed' => ['label' => 'Listed', 'class' => 'todo'],
                        'work_in_progress' => ['label' => 'In Progress', 'class' => 'in-progress'],
                        'completed' => ['label' => 'Completed', 'class' => 'done'],
                        'compromised' => ['label' => 'Compromised', 'class' => 'blocked'],
                        'under_maintenance' => ['label' => 'Maintenance', 'class' => 'review'],
                    ];
                    $totalReqs = $allFunctionalReqs->count() + $allNonFunctionalReqs->count();
                    $completedReqs = $allFunctionalReqs->where('implementation_status','completed')->count() + $allNonFunctionalReqs->where('implementation_status','completed')->count();
                    $inProgressReqs = $allFunctionalReqs->where('implementation_status','work_in_progress')->count() + $allNonFunctionalReqs->where('implementation_status','work_in_progress')->count();
                    $progress = $totalReqs > 0 ? round(($completedReqs / $totalReqs) * 100) : 0;
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div>
                        <p class="text-[13px] font-medium mb-3" style="color:var(--color-text)">Functional ({{ $allFunctionalReqs->count() }})</p>
                        <div class="space-y-1.5 max-h-80 overflow-y-auto">
                            @forelse($allFunctionalReqs as $req)
                                <div class="flex items-center justify-between gap-2 p-2 rounded-md text-[13px]" style="background:{{ ($req->implementation_status ?? 'listed') === 'completed' ? 'color-mix(in srgb, var(--color-success) 6%, transparent)' : 'transparent' }}">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-[11px] flex-shrink-0" style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $req->section_number }}</span>
                                        <span class="truncate" style="color:var(--color-text)">{{ $req->title }}</span>
                                    </div>
                                    @can('update', $project)
                                    <form action="{{ route('projects.requirements.update', [$project, 'functional', $req->id]) }}" method="POST" class="flex-shrink-0">
                                        @csrf @method('PATCH')
                                        <select name="implementation_status" onchange="this.form.submit()" class="gd-select h-6 text-[11px] py-0 w-24" style="padding:0 20px 0 6px">
                                            @foreach($reqStatuses as $val => $info)
                                                <option value="{{ $val }}" {{ ($req->implementation_status ?? 'listed') === $val ? 'selected' : '' }}>{{ $info['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    @else
                                        <span class="gd-badge gd-badge-{{ $reqStatuses[$req->implementation_status ?? 'listed']['class'] }} text-[9px]">{{ $reqStatuses[$req->implementation_status ?? 'listed']['label'] }}</span>
                                    @endcan
                                </div>
                            @empty
                                <p class="text-[12px] py-2" style="color:var(--color-text-muted)">No functional requirements</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <p class="text-[13px] font-medium mb-3" style="color:var(--color-text)">Non-Functional ({{ $allNonFunctionalReqs->count() }})</p>
                        <div class="space-y-1.5 max-h-80 overflow-y-auto">
                            @forelse($allNonFunctionalReqs as $req)
                                <div class="flex items-center justify-between gap-2 p-2 rounded-md text-[13px]" style="background:{{ ($req->implementation_status ?? 'listed') === 'completed' ? 'color-mix(in srgb, var(--color-success) 6%, transparent)' : 'transparent' }}">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-[11px] flex-shrink-0" style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $req->section_number }}</span>
                                        <span class="truncate" style="color:var(--color-text)">{{ $req->title }}</span>
                                        @if($req->category)
                                            <span class="gd-chip text-[9px]">{{ $req->category }}</span>
                                        @endif
                                    </div>
                                    @can('update', $project)
                                    <form action="{{ route('projects.requirements.update', [$project, 'non_functional', $req->id]) }}" method="POST" class="flex-shrink-0">
                                        @csrf @method('PATCH')
                                        <select name="implementation_status" onchange="this.form.submit()" class="gd-select h-6 text-[11px] py-0 w-24" style="padding:0 20px 0 6px">
                                            @foreach($reqStatuses as $val => $info)
                                                <option value="{{ $val }}" {{ ($req->implementation_status ?? 'listed') === $val ? 'selected' : '' }}>{{ $info['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    @else
                                        <span class="gd-badge gd-badge-{{ $reqStatuses[$req->implementation_status ?? 'listed']['class'] }} text-[9px]">{{ $reqStatuses[$req->implementation_status ?? 'listed']['label'] }}</span>
                                    @endcan
                                </div>
                            @empty
                                <p class="text-[12px] py-2" style="color:var(--color-text-muted)">No non-functional requirements</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if($totalReqs > 0)
                <div class="mt-5 pt-4" style="border-top:1px solid var(--color-border)">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[12px] font-medium" style="color:var(--color-text)">Implementation Progress</span>
                        <span class="text-[11px]" style="font-family:var(--font-mono);color:var(--color-text-muted)">{{ $completedReqs }}/{{ $totalReqs }} completed ({{ $progress }}%)</span>
                    </div>
                    <div class="gd-progress">
                        <div class="gd-progress-bar" style="width:{{ $progress }}%;background:{{ $progress>=70?'linear-gradient(90deg,var(--color-accent),var(--color-success))':($progress>=30?'linear-gradient(90deg,var(--color-warning),var(--color-accent))':'linear-gradient(90deg,var(--color-danger),var(--color-warning))') }}"></div>
                    </div>
                    <div class="flex gap-4 mt-2 text-[11px]" style="color:var(--color-text-faint)">
                        <span style="color:var(--color-success)">{{ $completedReqs }} Completed</span>
                        <span style="color:var(--color-warning)">{{ $inProgressReqs }} In Progress</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="gd-card p-5" style="border-left:3px solid var(--color-warning)">
            <p class="text-[13px] font-medium mb-2" style="color:var(--color-text)">No SRS Document</p>
            <p class="text-[12px] mb-3" style="color:var(--color-text-muted)">Create an SRS document to track requirements for this project.</p>
            <a href="{{ route('documentation.srs.create', ['project_id' => $project->id]) }}" class="gd-btn gd-btn-secondary gd-btn-sm">Create SRS</a>
        </div>
        @endif

        {{-- AI Task Generation --}}
        @can('update', $project)
        <div class="gd-card p-5" style="border-left:3px solid var(--color-purple)">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[13px] font-medium" style="color:var(--color-text)">AI Task Generation</p>
                    <p class="text-[12px] mt-1" style="color:var(--color-text-muted)">Generate tasks from your SRS requirements automatically</p>
                </div>
                <a href="{{ route('projects.ai-tasks.preview', $project) }}" class="gd-btn gd-btn-primary">Generate Tasks</a>
            </div>
        </div>
        @endcan

        {{-- Tasks Table --}}
        <div class="gd-card p-0 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid var(--color-border)">
                <div class="flex items-center gap-3">
                    <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Tasks</p>
                </div>
                <div class="flex items-center gap-2">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Filter tasks..." class="gd-input h-7 text-[12px] w-40">
                        <select name="status" class="gd-select h-7 text-[12px] w-28" onchange="this.form.submit()">
                            <option value="">All statuses</option>
                            @foreach(['To Do','In Progress','Review','Done'] as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="gd-btn gd-btn-secondary gd-btn-sm">Filter</button>
                    </form>
                    <button type="button" onclick="document.getElementById('createTaskPanel').classList.toggle('hidden')" class="gd-btn gd-btn-primary gd-btn-sm">Add Task</button>
                </div>
            </div>

            {{-- Quick Add Task Form --}}
            <div id="createTaskPanel" class="hidden px-5 py-4" style="border-bottom:1px solid var(--color-border);background:var(--color-surface-2)">
                <form action="{{ route('projects.tasks.store', $project) }}" method="POST" class="flex items-end gap-3 flex-wrap">
                    @csrf
                    <div class="flex-1 min-w-[200px]">
                        <label class="gd-label">Title</label>
                        <input type="text" name="title" required class="gd-input h-7 text-[12px]" placeholder="Task title...">
                    </div>
                    <div>
                        <label class="gd-label">Priority</label>
                        <select name="priority" class="gd-select h-7 text-[12px] w-24">
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="gd-label">Assign To</label>
                        <select name="assigned_to" class="gd-select h-7 text-[12px] w-36">
                            <option value="">Unassigned</option>
                            @foreach($members as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="gd-label">Est. Hours</label>
                        <input type="number" name="estimated_hours" step="0.5" min="0.5" max="200" class="gd-input h-7 text-[12px] w-20" placeholder="4">
                    </div>
                    <button type="submit" class="gd-btn gd-btn-primary gd-btn-sm">Create</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr style="border-bottom:1px solid var(--color-border)">
                            <th class="text-left px-5 py-2.5 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--color-text-faint)">Title</th>
                            <th class="text-left px-3 py-2.5 text-[11px] font-semibold uppercase tracking-wider hidden md:table-cell" style="color:var(--color-text-faint)">Requirement</th>
                            <th class="text-left px-3 py-2.5 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--color-text-faint)">Priority</th>
                            <th class="text-left px-3 py-2.5 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--color-text-faint)">Status</th>
                            <th class="text-left px-3 py-2.5 text-[11px] font-semibold uppercase tracking-wider hidden md:table-cell" style="color:var(--color-text-faint)">Assigned To</th>
                            <th class="text-right px-5 py-2.5 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--color-text-faint)">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:var(--color-border)">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gd-surface-3 transition-colors duration-120">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="gd-chip text-[10px] flex-shrink-0">T-{{ $task->id }}</span>
                                        <a href="{{ route('tasks.show', $task) }}" class="font-medium hover:underline truncate block max-w-[300px]" style="color:var(--color-text)">{{ $task->title }}</a>
                                        @if($task->is_scaffold)
                                            <span class="gd-badge gd-badge-purple text-[9px]">Scaffold</span>
                                        @elseif($task->scaffold_task_id)
                                            <span class="gd-chip text-[9px]" style="background:color-mix(in srgb, var(--color-purple) 8%, transparent);border-color:color-mix(in srgb, var(--color-purple) 20%, transparent);color:var(--color-purple)">SF#{{ $task->scaffold_task_id }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-3 hidden md:table-cell">
                                    @if($task->requirement)
                                        <span class="text-[11px]" style="font-family:var(--font-mono);color:var(--color-accent)">{{ $task->requirement->section_number }}</span>
                                    @else
                                        <span style="color:var(--color-text-faint)">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    @php
                                        $prioBadge = match($task->priority) { 'Critical' => 'critical', 'High' => 'high', 'Medium' => 'medium', default => 'low' };
                                    @endphp
                                    <span class="gd-badge gd-badge-{{ $prioBadge }}">{{ $task->priority }}</span>
                                </td>
                                <td class="px-3 py-3">
                                    @php
                                        $ts = match($task->status) { 'To Do' => 'todo', 'In Progress' => 'in-progress', 'Review' => 'review', 'Done' => 'done', default => 'todo' };
                                    @endphp
                                    <span class="gd-badge gd-badge-{{ $ts }}">{{ $task->status }}</span>
                                </td>
                                <td class="px-3 py-3 hidden md:table-cell" style="color:var(--color-text-muted)">
                                    {{ $task->assignee?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('tasks.show', $task) }}" class="gd-btn gd-btn-ghost gd-btn-icon-sm" title="View">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center">
                                    <p class="text-[13px] mb-3" style="color:var(--color-text-muted)">No tasks yet</p>
                                    <button type="button" onclick="document.getElementById('createTaskPanel').classList.toggle('hidden')" class="gd-btn gd-btn-primary gd-btn-sm">Add First Task</button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
