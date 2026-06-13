<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <span class="gd-chip">T-{{ $task->id }}</span>
                <h2 class="text-[18px] font-semibold truncate" style="font-family:var(--font-mono);color:var(--color-text)">{{ $task->title }}</h2>
                @if($task->is_scaffold)
                    <span class="gd-badge gd-badge-purple">Scaffold</span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('projects.show', $task->project) }}" class="gd-btn gd-btn-ghost gd-btn-sm">
                    <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ====== MAIN CONTENT (flex-1) ====== --}}
        <div class="lg:col-span-6 space-y-6">

            {{-- Task Details --}}
            <div class="gd-card p-5">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <p class="text-[12px] font-semibold uppercase tracking-wider mb-1" style="color:var(--color-text-muted)">Task Details</p>
                        <p class="text-[11px]" style="font-family:var(--font-mono);color:var(--color-text-faint)">Created by {{ $task->creator->name ?? 'Unknown' }} {{ $task->created_at->diffForHumans() }}</p>
                    </div>
                    @php $prioBadge = match($task->priority) { 'Critical' => 'critical', 'High' => 'high', 'Medium' => 'medium', default => 'low' }; @endphp
                    <span class="gd-badge gd-badge-{{ $prioBadge }}">{{ $task->priority }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <p class="gd-label">Status</p>
                        @php $ts = match($task->status) { 'To Do' => 'todo', 'In Progress' => 'in-progress', 'Review' => 'review', 'Done' => 'done', default => 'todo' }; @endphp
                        <span class="gd-badge gd-badge-{{ $ts }}">{{ $task->status }}</span>
                        @if($task->pendingStatusRequest)
                            <span class="gd-badge gd-badge-warning mt-1 block">Pending: {{ $task->pendingStatusRequest->requested_status }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="gd-label">Due Date</p>
                        <p class="text-[13px]" style="font-family:var(--font-mono);color:{{ $task->isOverdue() ? 'var(--color-danger)' : 'var(--color-text)' }}">
                            {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                            @if($task->due_date?->isPast() && $task->status !== 'Done')
                                <span style="color:var(--color-danger)"> (Overdue)</span>
                            @elseif($task->due_date?->isToday())
                                <span style="color:var(--color-orange)"> (Today)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="gd-label">Assigned To</p>
                        <p class="text-[13px]" style="color:var(--color-text)">{{ $task->assignee->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="gd-label">Project Owner</p>
                        <p class="text-[13px]" style="color:var(--color-text)">{{ $task->project->user->name }}</p>
                    </div>
                </div>

                @if($task->description)
                    <div class="mt-5 pt-5" style="border-top:1px solid var(--color-border)">
                        <p class="gd-label">Description</p>
                        <div class="text-[13px] leading-relaxed whitespace-pre-line rounded-md p-3" style="background:var(--color-base);color:var(--color-text)">{{ $task->description }}</div>
                    </div>
                @endif
            </div>

            {{-- Linked Requirement --}}
            @if($task->requirement)
            <div class="gd-card p-5">
                <p class="text-[12px] font-semibold uppercase tracking-wider mb-3" style="color:var(--color-text-muted)">Linked Requirement</p>
                <div class="rounded-md p-4" style="background:color-mix(in srgb, var(--color-accent) 6%, transparent);border:1px solid color-mix(in srgb, var(--color-accent) 15%, transparent)">
                    <div class="flex items-start gap-3">
                        <span class="gd-chip text-[11px]" style="background:color-mix(in srgb, var(--color-accent) 15%, transparent);border-color:transparent;color:var(--color-accent);font-weight:600">{{ $task->requirement->section_number }}</span>
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium" style="color:var(--color-text)">{{ $task->requirement->title }}</p>
                            <p class="text-[12px] mt-1" style="color:var(--color-text-muted)">{{ Str::limit($task->requirement->description, 200) }}</p>
                            <span class="text-[11px] mt-2 gd-chip">
                                {{ $task->requirement_type === \App\Models\SrsFunctionalRequirement::class ? 'Functional' : 'Non-Functional' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- SRS Reference --}}
            @if($srsDocument)
            <div class="gd-card p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">SRS Document</p>
                    <a href="{{ route('documentation.srs.edit', $srsDocument) }}" class="text-[12px] hover:underline" style="color:var(--color-accent)">View</a>
                </div>
                <p class="text-[13px] mb-3" style="color:var(--color-text)">{{ $srsDocument->title }}</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-md p-3 text-center" style="background:color-mix(in srgb, var(--color-accent) 6%, transparent)">
                        <p class="text-[20px] font-bold" style="font-family:var(--font-mono);color:var(--color-accent)">{{ $srsDocument->functionalRequirements->count() }}</p>
                        <p class="text-[11px]" style="color:var(--color-text-muted)">Functional</p>
                    </div>
                    <div class="rounded-md p-3 text-center" style="background:color-mix(in srgb, var(--color-purple) 6%, transparent)">
                        <p class="text-[20px] font-bold" style="font-family:var(--font-mono);color:var(--color-purple)">{{ $srsDocument->nonFunctionalRequirements->count() }}</p>
                        <p class="text-[11px]" style="color:var(--color-text-muted)">Non-Functional</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Status Change History --}}
            @if($task->statusRequests->count())
            <div class="gd-card p-5">
                <p class="text-[12px] font-semibold uppercase tracking-wider mb-3" style="color:var(--color-text-muted)">Status History</p>
                <div class="space-y-2">
                    @foreach($task->statusRequests as $req)
                        <div class="flex items-start gap-3 p-3 rounded-md text-[13px]"
                             style="background:{{ $req->isPending() ? 'color-mix(in srgb, var(--color-warning) 6%, transparent)' : ($req->isApproved() ? 'color-mix(in srgb, var(--color-success) 6%, transparent)' : 'color-mix(in srgb, var(--color-danger) 6%, transparent)') }}">
                            <span class="mt-0.5 flex-shrink-0">
                                @if($req->isPending()) <span style="color:var(--color-warning)">&#9679;</span>
                                @elseif($req->isApproved()) <span style="color:var(--color-success)">&#9679;</span>
                                @else <span style="color:var(--color-danger)">&#9679;</span>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <p style="color:var(--color-text)">
                                    <span class="font-medium">{{ $req->requester->name }}</span> requested
                                    <span class="font-medium">{{ $req->current_status }}</span> &rarr;
                                    <span class="font-medium">{{ $req->requested_status }}</span>
                                </p>
                                @if($req->notes)
                                    <p class="text-[12px] mt-1" style="color:var(--color-text-muted)">{{ $req->notes }}</p>
                                @endif
                                <p class="text-[11px] mt-1" style="font-family:var(--font-mono);color:var(--color-text-faint)">
                                    {{ $req->created_at->diffForHumans() }}
                                    @if($req->reviewed_at) &middot; Reviewed {{ $req->reviewed_at->diffForHumans() }} @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Comments --}}
            <div class="gd-card p-5">
                <x-task.comments :task="$task" />
            </div>

            {{-- Time Tracking --}}
            <div class="gd-card p-5">
                <x-task.time-logger :task="$task" />
            </div>
        </div>

        {{-- ====== METADATA COLUMN ====== --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Actions --}}
            <div class="gd-card p-5">
                <p class="text-[12px] font-semibold uppercase tracking-wider mb-4" style="color:var(--color-text-muted)">Actions</p>

                @if($isAssignee && !$task->pendingStatusRequest)
                    <form action="{{ route('tasks.request-status', $task) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <p class="gd-label">Request Status Change</p>
                            <select name="requested_status" class="gd-select h-7 text-[12px]" required>
                                <option value="">Select status...</option>
                                @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $st)
                                    @if($st !== $task->status)
                                        <option value="{{ $st }}">{{ $st }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <textarea name="notes" rows="2" class="gd-textarea text-[12px]" placeholder="Optional notes..."></textarea>
                        <button type="submit" class="gd-btn gd-btn-primary w-full">Submit Request</button>
                        <p class="text-[11px] text-center" style="color:var(--color-text-faint)">Requires owner approval</p>
                    </form>
                @elseif($isAssignee && $task->pendingStatusRequest)
                    <div class="rounded-md p-3 text-center" style="background:color-mix(in srgb, var(--color-warning) 8%, transparent);border:1px solid color-mix(in srgb, var(--color-warning) 20%, transparent)">
                        <p class="text-[13px] font-medium" style="color:var(--color-warning)">Pending Approval</p>
                        <p class="text-[12px] mt-1" style="color:var(--color-text-muted)">Waiting for &rarr; {{ $task->pendingStatusRequest->requested_status }}</p>
                    </div>
                @endif

                @if($isOwner && $task->pendingStatusRequest)
                    <div class="mt-4 pt-4" style="border-top:1px solid var(--color-border)">
                        <p class="text-[12px] font-medium mb-3" style="color:var(--color-text)">Review Request</p>
                        <div class="rounded-md p-3 mb-3 text-[12px]" style="background:color-mix(in srgb, var(--color-warning) 8%, transparent);border:1px solid color-mix(in srgb, var(--color-warning) 20%, transparent)">
                            <span class="font-medium">{{ $task->pendingStatusRequest->requester->name }}</span>
                            wants {{ $task->pendingStatusRequest->current_status }} &rarr; {{ $task->pendingStatusRequest->requested_status }}
                            @if($task->pendingStatusRequest->notes)
                                <p class="mt-1" style="color:var(--color-text-muted)">{{ $task->pendingStatusRequest->notes }}</p>
                            @endif
                        </div>
                        <form action="{{ route('tasks.review-status-request', $task->pendingStatusRequest) }}" method="POST" class="space-y-2">
                            @csrf
                            <textarea name="review_notes" rows="2" class="gd-textarea text-[12px]" placeholder="Review notes..."></textarea>
                            <div class="flex gap-2">
                                <button type="submit" name="action" value="approve" class="gd-btn gd-btn-primary flex-1">Approve</button>
                                <button type="submit" name="action" value="reject" class="gd-btn gd-btn-danger flex-1">Reject</button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($isOwner && !$task->pendingStatusRequest)
                    <form action="{{ route('tasks.update', $task) }}" method="POST" class="mt-4 pt-4" style="border-top:1px solid var(--color-border)">
                        @csrf @method('PUT')
                        <input type="hidden" name="title" value="{{ $task->title }}">
                        <p class="gd-label">Direct Status Update</p>
                        <select name="status" onchange="this.form.submit()" class="gd-select h-7 text-[12px]">
                            @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $st)
                                <option value="{{ $st }}" {{ $task->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] mt-1" style="color:var(--color-text-faint)">As owner, you can update directly</p>
                    </form>
                @endif
            </div>

            {{-- Quick Info --}}
            <div class="gd-card p-5">
                <p class="text-[12px] font-semibold uppercase tracking-wider mb-3" style="color:var(--color-text-muted)">Info</p>
                <dl class="space-y-2 text-[12px]">
                    <div class="flex justify-between">
                        <dt style="color:var(--color-text-muted)">Task ID</dt>
                        <dd style="font-family:var(--font-mono);color:var(--color-text)">T-{{ $task->id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt style="color:var(--color-text-muted)">Created</dt>
                        <dd style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $task->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt style="color:var(--color-text-muted)">Updated</dt>
                        <dd style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $task->updated_at->diffForHumans() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt style="color:var(--color-text-muted)">Your Role</dt>
                        <dd style="color:var(--color-text)">
                            @if($isOwner && $isAssignee) Owner & Assignee
                            @elseif($isOwner) Project Owner
                            @else Assignee
                            @endif
                        </dd>
                    </div>
                    @if($task->estimated_hours)
                    <div class="flex justify-between">
                        <dt style="color:var(--color-text-muted)">Estimated</dt>
                        <dd style="font-family:var(--font-mono);color:var(--color-text)">{{ $task->estimated_hours }}h</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- ====== AI CONTEXT PANEL (purple tint) ====== --}}
        <div class="lg:col-span-3 space-y-6">
            @if($task->component || $task->prompt_brief || $task->scaffoldTask || $task->predicted_files)
            <div class="gd-card p-5" style="border-top:3px solid color-mix(in srgb, var(--color-purple) 40%, transparent);background:color-mix(in srgb, var(--color-purple) 4%, var(--color-surface))">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="h-4 w-4" style="color:var(--color-purple)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
                    <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-purple)">AI Context</p>
                </div>

                @if($task->component)
                    <div class="mb-3">
                        <p class="gd-label">Component</p>
                        <span class="gd-chip" style="background:color-mix(in srgb, var(--color-purple) 10%, transparent);border-color:color-mix(in srgb, var(--color-purple) 25%, transparent);color:var(--color-purple)">{{ $task->component }}</span>
                    </div>
                @endif

                @if($task->scaffoldTask)
                    <div class="rounded-md p-3 mb-3 text-[12px]"
                         style="background:{{ $task->scaffoldTask->isScaffoldComplete() ? 'color-mix(in srgb, var(--color-success) 6%, transparent)' : 'color-mix(in srgb, var(--color-purple) 6%, transparent)' }};border:1px solid {{ $task->scaffoldTask->isScaffoldComplete() ? 'color-mix(in srgb, var(--color-success) 20%, transparent)' : 'color-mix(in srgb, var(--color-purple) 20%, transparent)' }}">
                        <p class="font-medium" style="color:var(--color-text)">Scaffold #{{ $task->scaffoldTask->id }}</p>
                        <p class="mt-1" style="color:var(--color-text-muted)">{{ $task->scaffoldTask->title }}</p>
                        <p class="mt-1" style="color:{{ $task->scaffoldTask->isScaffoldComplete() ? 'var(--color-success)' : 'var(--color-purple)' }}">
                            {{ $task->scaffoldTask->isScaffoldComplete() ? 'Complete' : 'This scaffold must be completed before this task can be marked done.' }}
                        </p>
                    </div>
                @endif

                @if($task->prompt_brief)
                    <div class="mb-3">
                        <p class="gd-label">Brief</p>
                        <p class="text-[12px]" style="color:var(--color-text-muted)">{{ $task->prompt_brief }}</p>
                    </div>
                @endif

                @if(!empty($task->predicted_files))
                    <div class="mb-3">
                        <p class="gd-label">Predicted Files</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($task->predicted_files as $file)
                                <code class="text-[10px] px-2 py-0.5 rounded" style="background:var(--color-base);color:var(--color-text-muted);font-family:var(--font-mono)">{{ $file }}</code>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($task->prompt_section)
                    <div class="mt-3" x-data="{ show: false }">
                        <button @click="show = !show" class="gd-btn gd-btn-ghost gd-btn-sm" style="color:var(--color-purple)">
                            <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            <span x-text="show ? 'Hide Prompt' : 'View Coding Prompt'"></span>
                        </button>
                        <pre x-show="show" x-cloak
                             x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             class="mt-3 whitespace-pre-wrap text-[11px] rounded-md p-3 overflow-x-auto"
                             style="background:var(--color-base);color:var(--color-text);font-family:var(--font-mono);line-height:1.6;border:1px solid var(--color-border)">{{ $task->prompt_section }}</pre>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
