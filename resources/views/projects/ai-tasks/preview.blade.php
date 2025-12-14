<x-app-layout>
    @vite(['resources/js/modules/ai-tasks.js'])
    
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🤖 {{ __('AI Task Generation') }} - {{ $project->name }}
            </h2>
            <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-800">
                ← Back to Project
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Project Context -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">📋 Project Context</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Project Details</h4>
                            <p class="text-gray-600 text-sm">{{ $project->description ?? 'No description provided.' }}</p>
                            <div class="mt-2 text-sm">
                                <span class="font-medium">Status:</span> {{ ucfirst($project->status) }}
                                <span class="ml-4 font-medium">Type:</span> {{ ucfirst($project->type ?? 'Solo') }}
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Requirements Summary</h4>
                            @if($srsDocument)
                                <div class="text-sm text-gray-600">
                                    <p>📌 {{ $srsDocument->functionalRequirements->count() }} Functional Requirements</p>
                                    <p>⚙️ {{ $srsDocument->nonFunctionalRequirements->count() }} Non-Functional Requirements</p>
                                </div>
                            @else
                                <p class="text-yellow-600 text-sm">⚠️ No SRS document found. Consider creating one first for better task generation.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Composition -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">👥 Team Composition</h3>
                    @if($teamMembers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($teamMembers as $member)
                                <div class="border rounded-lg p-4 hover:shadow-sm transition">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                            <p class="text-sm text-indigo-600">{{ $member->role_name ?? $member->role ?? 'Team Member' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        {{ $member->active_tasks }} active tasks
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No team members assigned to this project.</p>
                    @endif
                </div>
            </div>

            <!-- Generate Tasks Button -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white text-center">
                    <h3 class="text-xl font-bold mb-2">Ready to Generate Tasks?</h3>
                    <p class="mb-4 opacity-90">AI will analyze your project requirements and team composition to create optimized tasks.</p>
                    <button type="button" id="generateBtn" onclick="generateTasks()" 
                            class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition shadow-lg">
                        🚀 Generate Tasks with AI
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">AI is analyzing your project and generating tasks...</p>
                    <p class="text-sm text-gray-400 mt-2">This may take a few moments.</p>
                </div>
            </div>

            <!-- Generated Tasks Preview -->
            <div id="tasksPreview" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">🎯 Generated Tasks Preview</h3>
                        <div class="flex gap-2">
                            <button type="button" onclick="generateTasks()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                                🔄 Regenerate
                            </button>
                            <button type="button" id="saveBtn" onclick="saveTasks()" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                ✅ Save Tasks
                            </button>
                        </div>
                    </div>

                    <div id="tasksList" class="space-y-4">
                        <!-- Tasks will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div id="errorState" class="hidden bg-red-50 border border-red-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-red-800 mb-2">❌ Generation Failed</h3>
                    <p id="errorMessage" class="text-red-600"></p>
                    <button type="button" onclick="generateTasks()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let generatedTasks = [];
        const teamMembers = @json($teamMembers);
        const systemRoles = @json($systemRoles);
        
        @php
            $functionalReqs = $srsDocument?->functionalRequirements->map(fn($req) => [
                'id' => $req->id,
                'section' => $req->section_number ?? '',
                'title' => $req->title,
                'description' => $req->description ?? '',
                'priority' => $req->priority ?? 'Medium',
                'acceptance_criteria' => $req->acceptance_criteria ?? '',
            ])->toArray() ?? [];
            
            $nonFunctionalReqs = $srsDocument?->nonFunctionalRequirements->map(fn($req) => [
                'id' => $req->id,
                'section' => $req->section_number ?? '',
                'title' => $req->title,
                'description' => $req->description ?? '',
                'category' => $req->category ?? '',
                'priority' => $req->priority ?? 'Medium',
                'target_value' => $req->target_value ?? '',
            ])->toArray() ?? [];
            
            $teamData = $teamMembers->map(fn($m) => [
                'user_id' => $m->id,
                'name' => $m->name,
                'role' => $m->role_name ?? $m->role,
                'active_tasks' => $m->active_tasks,
            ])->toArray();
        @endphp
        
        const projectContext = {
            project: {
                name: @json($project->name),
                description: @json($project->description),
                type: @json($project->type),
                status: @json($project->status)
            },
            team: @json($teamData),
            functional_requirements: @json($functionalReqs),
            non_functional_requirements: @json($nonFunctionalReqs),
            available_roles: @json($systemRoles->pluck('name'))
        };

        async function generateTasks() {
            // Show loading state
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('tasksPreview').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');
            document.getElementById('generateBtn').disabled = true;

            try {
                // Wait for module to load if not ready yet
                if (!window.geminiAI) {
                    console.log('Waiting for AI module to load...');
                    await new Promise(resolve => {
                        const checkInterval = setInterval(() => {
                            if (window.geminiAI) {
                                clearInterval(checkInterval);
                                resolve();
                            }
                        }, 100);
                        // Timeout after 5 seconds
                        setTimeout(() => {
                            clearInterval(checkInterval);
                            resolve();
                        }, 5000);
                    });
                }

                if (!window.geminiAI) {
                    throw new Error('AI module failed to load. Please refresh the page.');
                }

                // Use Gemini API to generate tasks (loaded via ai-tasks.js module)
                const result = await window.geminiAI.generateTasks(projectContext);
                
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('generateBtn').disabled = false;

                if (result.success) {
                    // Assign tasks to team members
                    generatedTasks = assignTasksToTeam(result.tasks);
                    renderTasks(generatedTasks);
                    document.getElementById('tasksPreview').classList.remove('hidden');
                } else {
                    document.getElementById('errorMessage').textContent = result.error || 'Unknown error occurred';
                    document.getElementById('errorState').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Task generation error:', error);
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('generateBtn').disabled = false;
                document.getElementById('errorMessage').textContent = 'Error: ' + error.message;
                document.getElementById('errorState').classList.remove('hidden');
            }
        }

        function assignTasksToTeam(tasks) {
            // Simple assignment based on role match and workload
            return tasks.map(task => {
                const assignee = findBestAssignee(task.required_role);
                return {
                    ...task,
                    assigned_to: assignee?.id || null,
                    assignee_name: assignee?.name || null
                };
            });
        }

        function findBestAssignee(requiredRole) {
            // Find exact match with lowest workload
            let candidates = teamMembers.filter(m => m.role === requiredRole || m.role_name === requiredRole);
            
            if (candidates.length === 0) {
                // Try Full Stack Developer as fallback
                candidates = teamMembers.filter(m => m.role === 'Full Stack Developer' || m.role_name === 'Full Stack Developer');
            }
            
            if (candidates.length === 0) {
                // Assign to member with lowest workload
                candidates = teamMembers;
            }

            // Sort by active tasks and return first
            return candidates.sort((a, b) => (a.active_tasks || 0) - (b.active_tasks || 0))[0];
        }

        window.renderTasks = renderTasks;

        function renderTasks(tasks) {
            const container = document.getElementById('tasksList');
            container.innerHTML = '';

            const priorityColors = {
                'Critical': 'bg-red-100 text-red-800 border-red-200',
                'High': 'bg-orange-100 text-orange-800 border-orange-200',
                'Medium': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'Low': 'bg-green-100 text-green-800 border-green-200',
            };

            tasks.forEach((task, index) => {
                const card = document.createElement('div');
                card.className = 'border rounded-lg p-4 hover:shadow-md transition';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <input type="text" name="tasks[${index}][title]" value="${escapeHtml(task.title)}" 
                                   class="w-full font-medium text-gray-900 border-0 border-b border-transparent hover:border-gray-300 focus:border-indigo-500 focus:ring-0 p-0"
                                   onchange="updateTask(${index}, 'title', this.value)">
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <span class="px-2 py-1 text-xs rounded-full border ${priorityColors[task.priority] || 'bg-gray-100'}">${task.priority}</span>
                            <span class="text-xs text-gray-500">${task.estimated_hours || 0}h</span>
                            <button type="button" onclick="removeTask(${index})" class="text-red-500 hover:text-red-700 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea name="tasks[${index}][description]" rows="2"
                                  class="w-full text-sm text-gray-600 border border-gray-200 rounded p-2 focus:ring-indigo-500"
                                  onchange="updateTask(${index}, 'description', this.value)">${escapeHtml(task.description || '')}</textarea>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Priority</label>
                            <select name="tasks[${index}][priority]" class="w-full border-gray-300 rounded text-sm" onchange="updateTask(${index}, 'priority', this.value)">
                                <option value="Low" ${task.priority === 'Low' ? 'selected' : ''}>Low</option>
                                <option value="Medium" ${task.priority === 'Medium' ? 'selected' : ''}>Medium</option>
                                <option value="High" ${task.priority === 'High' ? 'selected' : ''}>High</option>
                                <option value="Critical" ${task.priority === 'Critical' ? 'selected' : ''}>Critical</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Required Role</label>
                            <select name="tasks[${index}][required_role]" class="w-full border-gray-300 rounded text-sm" onchange="updateTask(${index}, 'required_role', this.value)">
                                ${systemRoles.map(r => `<option value="${r.name}" ${task.required_role === r.name ? 'selected' : ''}>${r.name}</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Estimate (hours)</label>
                            <input type="number" name="tasks[${index}][estimated_hours]" value="${task.estimated_hours || ''}" min="1" max="200"
                                   class="w-full border-gray-300 rounded text-sm" onchange="updateTask(${index}, 'estimated_hours', parseInt(this.value))">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Assign To</label>
                            <select name="tasks[${index}][assigned_to]" class="w-full border-gray-300 rounded text-sm" onchange="updateTask(${index}, 'assigned_to', this.value || null)">
                                <option value="">Unassigned</option>
                                ${teamMembers.map(m => `<option value="${m.id}" ${task.assigned_to == m.id ? 'selected' : ''}>${m.name} (${m.role || 'Member'})</option>`).join('')}
                            </select>
                        </div>
                    </div>
                    ${task.requirement_type ? `
                    <div class="mt-2 text-xs text-gray-500">
                        📌 Linked to ${task.requirement_type === 'functional' ? 'FR' : 'NFR'} #${task.requirement_id}
                    </div>
                    ` : ''}
                `;
                container.appendChild(card);
            });

            // Add summary
            const summary = document.createElement('div');
            summary.className = 'mt-6 p-4 bg-gray-50 rounded-lg';
            const totalHours = tasks.reduce((sum, t) => sum + (t.estimated_hours || 0), 0);
            summary.innerHTML = `
                <div class="flex justify-between text-sm">
                    <span><strong>${tasks.length}</strong> tasks generated</span>
                    <span>Total estimate: <strong>${totalHours}</strong> hours</span>
                </div>
            `;
            container.appendChild(summary);
        }

        function updateTask(index, field, value) {
            if (generatedTasks[index]) {
                generatedTasks[index][field] = value;
            }
        }

        function removeTask(index) {
            generatedTasks.splice(index, 1);
            renderTasks(generatedTasks);
        }

        function saveTasks() {
            if (generatedTasks.length === 0) {
                alert('No tasks to save.');
                return;
            }

            document.getElementById('saveBtn').disabled = true;
            document.getElementById('saveBtn').textContent = 'Saving...';

            fetch('{{ route('projects.ai-tasks.store', $project) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ tasks: generatedTasks })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect || '{{ route('projects.show', $project) }}';
                } else {
                    alert('Error: ' + (data.error || 'Failed to save tasks'));
                    document.getElementById('saveBtn').disabled = false;
                    document.getElementById('saveBtn').textContent = '✅ Save Tasks';
                }
            })
            .catch(error => {
                alert('Network error: ' + error.message);
                document.getElementById('saveBtn').disabled = false;
                document.getElementById('saveBtn').textContent = '✅ Save Tasks';
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
    @endpush
</x-app-layout>
