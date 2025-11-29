<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $task->title }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Project: <a href="{{ route('projects.show', $task->project) }}" class="text-indigo-600 hover:underline">{{ $task->project->name }}</a>
                </p>
            </div>
            <a href="{{ route('tasks.my-tasks') }}" class="text-sm text-indigo-600 hover:underline">← Back to My Tasks</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Task Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Task Details Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Task Details</h3>
                                    <p class="text-sm text-gray-500">Created by {{ $task->creator->name ?? 'Unknown' }} on {{ $task->created_at->format('M d, Y') }}</p>
                                </div>
                                @php
                                    $priorityColors = [
                                        'Critical' => 'bg-red-100 text-red-800 border-red-300',
                                        'High' => 'bg-orange-100 text-orange-800 border-orange-300',
                                        'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'Low' => 'bg-green-100 text-green-800 border-green-300',
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $task->priority }} Priority
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Current Status</label>
                                    @php
                                        $statusColors = [
                                            'To Do' => 'bg-gray-100 text-gray-800',
                                            'In Progress' => 'bg-blue-100 text-blue-800',
                                            'Review' => 'bg-yellow-100 text-yellow-800',
                                            'Done' => 'bg-green-100 text-green-800',
                                        ];
                                    @endphp
                                    <span class="mt-1 inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$task->status] ?? 'bg-gray-100' }}">
                                        {{ $task->status }}
                                    </span>
                                    @if($task->pendingStatusRequest)
                                        <span class="ml-2 text-xs text-amber-600 font-medium">
                                            ⏳ Pending change to "{{ $task->pendingStatusRequest->requested_status }}"
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Due Date</label>
                                    <p class="mt-1 text-gray-900">
                                        @if($task->due_date)
                                            {{ $task->due_date->format('M d, Y') }}
                                            @if($task->due_date->isPast() && $task->status !== 'Done')
                                                <span class="text-red-600 text-sm">(Overdue)</span>
                                            @elseif($task->due_date->isToday())
                                                <span class="text-amber-600 text-sm">(Due today)</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">No due date set</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Assigned To</label>
                                    <p class="mt-1 text-gray-900">{{ $task->assignee->name ?? 'Unassigned' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Project Owner</label>
                                    <p class="mt-1 text-gray-900">{{ $task->project->user->name }}</p>
                                </div>
                            </div>

                            @if($task->description)
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                                    <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg">
                                        {!! nl2br(e($task->description)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Linked Requirement Card -->
                    @if($task->requirement)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Linked Requirement</h3>
                                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <span class="px-2 py-1 bg-indigo-600 text-white text-xs font-bold rounded">
                                            {{ $task->requirement->section_number }}
                                        </span>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $task->requirement->title }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->requirement->description, 200) }}</p>
                                            <span class="inline-block mt-2 text-xs px-2 py-1 bg-gray-200 text-gray-700 rounded">
                                                {{ $task->requirement_type === \App\Models\SrsFunctionalRequirement::class ? 'Functional' : 'Non-Functional' }} Requirement
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- SRS Document Reference -->
                    @if($srsDocument)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">📄 Project SRS Document</h3>
                                    <a href="{{ route('documentation.srs.edit', $srsDocument) }}" class="text-sm text-indigo-600 hover:underline">View Full SRS →</a>
                                </div>
                                <p class="text-sm text-gray-600 mb-4">{{ $srsDocument->title }}</p>
                                
                                <!-- Requirements Summary -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <div class="text-2xl font-bold text-blue-700">{{ $srsDocument->functionalRequirements->count() }}</div>
                                        <div class="text-sm text-blue-600">Functional Requirements</div>
                                    </div>
                                    <div class="bg-purple-50 p-3 rounded-lg">
                                        <div class="text-2xl font-bold text-purple-700">{{ $srsDocument->nonFunctionalRequirements->count() }}</div>
                                        <div class="text-sm text-purple-600">Non-Functional Requirements</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Status Change History -->
                    @if($task->statusRequests->count())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">📜 Status Change History</h3>
                                <div class="space-y-3">
                                    @foreach($task->statusRequests as $request)
                                        <div class="flex items-start gap-3 p-3 rounded-lg {{ $request->isPending() ? 'bg-amber-50 border border-amber-200' : ($request->isApproved() ? 'bg-green-50' : 'bg-red-50') }}">
                                            <div class="flex-shrink-0">
                                                @if($request->isPending())
                                                    <span class="text-amber-500">⏳</span>
                                                @elseif($request->isApproved())
                                                    <span class="text-green-500">✅</span>
                                                @else
                                                    <span class="text-red-500">❌</span>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm">
                                                    <span class="font-medium">{{ $request->requester->name }}</span>
                                                    requested change from 
                                                    <span class="font-medium">{{ $request->current_status }}</span>
                                                    to 
                                                    <span class="font-medium">{{ $request->requested_status }}</span>
                                                </p>
                                                @if($request->notes)
                                                    <p class="text-xs text-gray-600 mt-1">Notes: {{ $request->notes }}</p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $request->created_at->diffForHumans() }}
                                                    @if($request->reviewed_at)
                                                        · Reviewed by {{ $request->reviewer->name ?? 'Unknown' }} {{ $request->reviewed_at->diffForHumans() }}
                                                    @endif
                                                </p>
                                                @if($request->review_notes)
                                                    <p class="text-xs text-gray-600 mt-1">Review notes: {{ $request->review_notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Actions Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                            
                            @if($isAssignee && !$task->pendingStatusRequest)
                                <!-- Request Status Change Form (for assignees) -->
                                <form action="{{ route('tasks.request-status', $task) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Request Status Change</label>
                                        <select name="requested_status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                            <option value="">Select new status...</option>
                                            @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $status)
                                                @if($status !== $task->status)
                                                    <option value="{{ $status }}">{{ $status }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                                        <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Explain why you're requesting this change..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                        Submit Request
                                    </button>
                                    <p class="text-xs text-gray-500">Status changes require approval from the project owner.</p>
                                </form>
                            @elseif($isAssignee && $task->pendingStatusRequest)
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-center">
                                    <p class="text-amber-800 font-medium">⏳ Pending Request</p>
                                    <p class="text-sm text-amber-600 mt-1">
                                        Waiting for owner approval to change status to "{{ $task->pendingStatusRequest->requested_status }}"
                                    </p>
                                </div>
                            @endif

                            @if($isOwner && $task->pendingStatusRequest)
                                <!-- Review Request Form (for owners) -->
                                <div class="mt-4 border-t pt-4">
                                    <h4 class="font-medium text-gray-900 mb-3">Review Pending Request</h4>
                                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                                        <p class="text-sm">
                                            <span class="font-medium">{{ $task->pendingStatusRequest->requester->name }}</span>
                                            wants to change status from 
                                            <span class="font-medium">{{ $task->pendingStatusRequest->current_status }}</span>
                                            to 
                                            <span class="font-medium">{{ $task->pendingStatusRequest->requested_status }}</span>
                                        </p>
                                        @if($task->pendingStatusRequest->notes)
                                            <p class="text-xs text-gray-600 mt-2">Notes: {{ $task->pendingStatusRequest->notes }}</p>
                                        @endif
                                    </div>
                                    <form action="{{ route('tasks.review-status-request', $task->pendingStatusRequest) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Review Notes (optional)</label>
                                            <textarea name="review_notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Add a note..."></textarea>
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="submit" name="action" value="approve" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                                ✓ Approve
                                            </button>
                                            <button type="submit" name="action" value="reject" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                                ✗ Reject
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if($isOwner && !$task->pendingStatusRequest)
                                <!-- Direct Status Change (for owners) -->
                                <form action="{{ route('tasks.update', $task) }}" method="POST" class="mt-4 border-t pt-4">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="title" value="{{ $task->title }}">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Update Status Directly</label>
                                    <select name="status" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $status)
                                            <option value="{{ $status }}" {{ $task->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">As owner, you can update status directly.</p>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Other Pending Requests (for owners) -->
                    @if($isOwner && $pendingRequests->count() > 1)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Other Pending Requests</h3>
                                <div class="space-y-3">
                                    @foreach($pendingRequests as $request)
                                        @if($request->task_id !== $task->id)
                                            <a href="{{ route('tasks.show', $request->task) }}" class="block p-3 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition">
                                                <p class="font-medium text-sm">{{ $request->task->title }}</p>
                                                <p class="text-xs text-gray-600">
                                                    {{ $request->requester->name }} → {{ $request->requested_status }}
                                                </p>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Info</h3>
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Task ID</dt>
                                    <dd class="text-gray-900 font-mono">#{{ $task->id }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Created</dt>
                                    <dd class="text-gray-900">{{ $task->created_at->format('M d, Y') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Last Updated</dt>
                                    <dd class="text-gray-900">{{ $task->updated_at->diffForHumans() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Your Role</dt>
                                    <dd class="text-gray-900">
                                        @if($isOwner && $isAssignee)
                                            Owner & Assignee
                                        @elseif($isOwner)
                                            Project Owner
                                        @else
                                            Assignee
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
