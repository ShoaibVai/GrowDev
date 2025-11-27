<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('Edit SRS Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('documentation.srs.update', $srsDocument) }}" class="space-y-8">
                @csrf
                @method('PUT')

            <!-- Basic Information Section -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">📝 Basic Information</h2>

                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Document Title</label>
                    <input type="text" id="title" name="title" required value="{{ $srsDocument->title }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->description }}</textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="project_overview" class="block text-sm font-medium text-gray-700 mb-2">Project Overview</label>
                        <textarea id="project_overview" name="project_overview" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->project_overview }}</textarea>
                    </div>
                    <div>
                        <label for="scope" class="block text-sm font-medium text-gray-700 mb-2">Scope</label>
                        <textarea id="scope" name="scope" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->scope }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="constraints" class="block text-sm font-medium text-gray-700 mb-2">Constraints</label>
                        <textarea id="constraints" name="constraints" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->constraints }}</textarea>
                    </div>
                    <div>
                        <label for="assumptions" class="block text-sm font-medium text-gray-700 mb-2">Assumptions</label>
                        <textarea id="assumptions" name="assumptions" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->assumptions }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Functional Requirements Section -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">✅ Functional Requirements</h2>
                    <button type="button" onclick="addFunctionalRequirement()" 
                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition font-semibold">
                        + Add Requirement
                    </button>
                </div>

                <div id="functional-requirements-container">
                    @forelse ($functionalRequirements as $index => $req)
                        <div class="requirement-item mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200" data-index="{{ $index }}">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Requirement ID</label>
                                    <input type="text" name="functional_requirements[{{ $index }}][requirement_id]" 
                                           value="{{ $req->requirement_id }}" required
                                           placeholder="REQ-001"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                    <input type="text" name="functional_requirements[{{ $index }}][title]" 
                                           value="{{ $req->title }}" required
                                           placeholder="Requirement Title"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                    <select name="functional_requirements[{{ $index }}][priority]" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                        <option value="low" @selected($req->priority === 'low')>Low</option>
                                        <option value="medium" @selected($req->priority === 'medium')>Medium</option>
                                        <option value="high" @selected($req->priority === 'high')>High</option>
                                        <option value="critical" @selected($req->priority === 'critical')>Critical</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="functional_requirements[{{ $index }}][description]" required rows="2"
                                          placeholder="Describe the functional requirement..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $req->description }}</textarea>
                            </div>

                            <!-- UX Considerations -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-gray-700">🎨 UX Considerations</label>
                                    <button type="button" onclick="addUxItem(this)" 
                                            class="text-sm px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        + Add UX Item
                                    </button>
                                </div>
                                <div class="ux-items-container" data-index="{{ $index }}">
                                    @if ($req->ux_considerations && count($req->ux_considerations) > 0)
                                        @foreach ($req->ux_considerations as $uxIndex => $uxItem)
                                            <div class="ux-item flex gap-2 mb-2">
                                                <input type="text" 
                                                       name="functional_requirements[{{ $index }}][ux_considerations][]" 
                                                       value="{{ $uxItem }}"
                                                       placeholder="e.g., Responsive design, Dark mode support"
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                                                <button type="button" onclick="removeUxItem(this)" 
                                                        class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                                    ✕
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Remove Button -->
                            <div class="flex justify-end">
                                <button type="button" onclick="removeRequirement(this)" 
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                    🗑️ Remove Requirement
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No functional requirements added yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6">
                <a href="{{ route('documentation.srs.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    ← Back
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                    💾 Save SRS
                </button>
                <a href="{{ route('documentation.srs.pdf', $srsDocument) }}" 
                   class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    📥 Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let requirementCounter = {{ $functionalRequirements->count() }};

function addFunctionalRequirement() {
    const container = document.getElementById('functional-requirements-container');
    const index = requirementCounter++;
    
    const html = `
        <div class="requirement-item mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200" data-index="${index}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Requirement ID</label>
                    <input type="text" name="functional_requirements[${index}][requirement_id]" 
                           placeholder="REQ-001" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="functional_requirements[${index}][title]" 
                           placeholder="Requirement Title" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select name="functional_requirements[${index}][priority]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="functional_requirements[${index}][description]" required rows="2"
                          placeholder="Describe the functional requirement..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">🎨 UX Considerations</label>
                    <button type="button" onclick="addUxItem(this)" 
                            class="text-sm px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                        + Add UX Item
                    </button>
                </div>
                <div class="ux-items-container" data-index="${index}"></div>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="removeRequirement(this)" 
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                    🗑️ Remove Requirement
                </button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function addUxItem(button) {
    const container = button.closest('.requirement-item').querySelector('.ux-items-container');
    const reqIndex = container.dataset.index;
    
    const html = `
        <div class="ux-item flex gap-2 mb-2">
            <input type="text" 
                   name="functional_requirements[${reqIndex}][ux_considerations][]" 
                   placeholder="e.g., Responsive design, Dark mode support"
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
            <button type="button" onclick="removeUxItem(this)" 
                    class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                ✕
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function removeUxItem(button) {
    button.closest('.ux-item').remove();
}

function removeRequirement(button) {
    button.closest('.requirement-item').remove();
}
</script>
        </div>
    </div>
</x-app-layout>
