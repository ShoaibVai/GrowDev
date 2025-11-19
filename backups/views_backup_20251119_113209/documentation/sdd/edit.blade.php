<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏗️ {{ __('Edit SDD Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
<script src="https://cdn.jsdelivr.net/npm/mermaid@latest/dist/mermaid.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<style>
    .diagram-preview {
        background-color: #f9fafb;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 20px;
        min-height: 300px;
        overflow-x: auto;
    }
    
    .mermaid {
        display: flex;
        justify-content: center;
    }
    
    .diagram-container {
        background-color: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .tab-buttons {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .tab-btn {
        padding: 10px 16px;
        background-color: #f3f4f6;
        border: none;
        border-radius: 6px 6px 0 0;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .tab-btn.active {
        background-color: #4f46e5;
        color: white;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
</style>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">🏗️ Edit SDD Document</h1>

        <form method="POST" action="{{ route('documentation.sdd.update', $sddDocument) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">📝 Basic Information</h2>

                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Document Title</label>
                    <input type="text" id="title" name="title" required value="{{ $sddDocument->title }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $sddDocument->description }}</textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="design_overview" class="block text-sm font-medium text-gray-700 mb-2">Design Overview</label>
                        <textarea id="design_overview" name="design_overview" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $sddDocument->design_overview }}</textarea>
                    </div>
                    <div>
                        <label for="architecture_overview" class="block text-sm font-medium text-gray-700 mb-2">Architecture Overview</label>
                        <textarea id="architecture_overview" name="architecture_overview" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $sddDocument->architecture_overview }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Components Section -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">🔧 Components</h2>
                    <button type="button" onclick="addComponent()" 
                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition font-semibold">
                        + Add Component
                    </button>
                </div>

                <div id="components-container">
                    @forelse ($components as $index => $comp)
                        <div class="component-item mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200" data-index="{{ $index }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Component Name</label>
                                    <input type="text" name="components[{{ $index }}][component_name]" 
                                           value="{{ $comp->component_name }}" required
                                           placeholder="e.g., User Service"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Diagram Type</label>
                                    <select name="components[{{ $index }}][diagram_type]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                        <option value="mermaid" @selected($comp->diagram_type === 'mermaid')>Mermaid Diagram</option>
                                        <option value="custom" @selected($comp->diagram_type === 'custom')>Custom Diagram</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="components[{{ $index }}][description]" required rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $comp->description }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Responsibility</label>
                                <textarea name="components[{{ $index }}][responsibility]" required rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $comp->responsibility }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Interfaces</label>
                                <textarea name="components[{{ $index }}][interfaces]" rows="2"
                                          placeholder="List interfaces exposed by this component..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $comp->interfaces }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" onclick="removeComponent(this)" 
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                    🗑️ Remove Component
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No components added yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Diagrams Section with Mermaid Support -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">📊 Diagrams</h2>
                    <div class="space-x-2">
                        <button type="button" onclick="showTab('text-to-diagram')" 
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition font-semibold">
                            ✨ AI Convert Text
                        </button>
                        <button type="button" onclick="showTab('manual-diagram')" 
                                class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 transition font-semibold">
                            + Manual Diagram
                        </button>
                    </div>
                </div>

                <!-- Tab: Text to Diagram -->
                <div id="text-to-diagram" class="tab-content">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-blue-900 mb-4">🤖 Convert Text to Mermaid Diagram</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Text Description</label>
                                <textarea id="text-description" rows="4"
                                          placeholder="Describe your diagram in plain text...
Example: The user logs in, the system validates credentials, returns a token if valid..."
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diagram Type</label>
                                <select id="diagram-type" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="flowchart">Flowchart</option>
                                    <option value="sequence">Sequence Diagram</option>
                                    <option value="class">Class Diagram</option>
                                    <option value="state">State Diagram</option>
                                </select>
                            </div>
                            <button type="button" onclick="convertTextToDiagram()" 
                                    class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                🔄 Generate Mermaid Diagram
                            </button>
                        </div>

                        <!-- Preview -->
                        <div id="mermaid-preview" class="mt-6 hidden">
                            <h4 class="font-bold text-gray-900 mb-4">Preview:</h4>
                            <div id="mermaid-render" class="diagram-preview"></div>
                            <button type="button" onclick="saveMermaidDiagram()" 
                                    class="mt-4 w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                ✅ Save this Diagram
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tab: Manual Diagram -->
                <div id="manual-diagram" class="tab-content">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-purple-900 mb-4">📐 Create Manual Diagram</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diagram Name</label>
                                <input type="text" id="manual-diagram-name" 
                                       placeholder="e.g., System Architecture"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mermaid Code</label>
                                <textarea id="manual-diagram-code" rows="6"
                                          placeholder="Enter Mermaid syntax...
graph TD
    A[User] --> B[System]
    B --> C[Database]"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 font-mono text-sm"></textarea>
                            </div>
                            <button type="button" onclick="previewManualDiagram()" 
                                    class="w-full px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                👁️ Preview Diagram
                            </button>
                        </div>

                        <!-- Manual Preview -->
                        <div id="manual-preview" class="mt-6 hidden">
                            <h4 class="font-bold text-gray-900 mb-4">Preview:</h4>
                            <div id="manual-render" class="diagram-preview"></div>
                            <button type="button" onclick="saveManualDiagram()" 
                                    class="mt-4 w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                ✅ Save this Diagram
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Saved Diagrams List -->
                <div id="diagrams-list" class="mt-8">
                    <h3 class="font-bold text-gray-900 mb-4">📋 Saved Diagrams</h3>
                    @forelse ($diagrams as $index => $diagram)
                        <div class="diagram-container border-l-4 border-purple-500">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $diagram->diagram_name }}</h4>
                                    <p class="text-sm text-gray-600">Type: {{ ucfirst($diagram->diagram_type) }}</p>
                                </div>
                                <button type="button" onclick="removeDiagram(this, {{ $index }})" 
                                        class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                    ✕ Remove
                                </button>
                            </div>
                            <input type="hidden" name="diagrams[{{ $index }}][diagram_name]" value="{{ $diagram->diagram_name }}">
                            <input type="hidden" name="diagrams[{{ $index }}][diagram_type]" value="{{ $diagram->diagram_type }}">
                            <input type="hidden" name="diagrams[{{ $index }}][diagram_content]" value="{{ $diagram->diagram_content }}">
                            <div class="diagram-preview">
                                <div class="mermaid">{{ $diagram->diagram_content }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-6">No diagrams created yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6">
                <a href="{{ route('documentation.sdd.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    ← Back
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                    💾 Save SDD
                </button>
                <a href="{{ route('documentation.sdd.pdf', $sddDocument) }}" 
                   class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    📥 Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let componentCounter = {{ $components->count() }};
let diagramCounter = {{ $diagrams->count() }};
let tempDiagrams = [];

function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}

function addComponent() {
    const container = document.getElementById('components-container');
    const index = componentCounter++;
    
    const html = `
        <div class="component-item mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200" data-index="${index}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Component Name</label>
                    <input type="text" name="components[${index}][component_name]" 
                           placeholder="e.g., User Service" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diagram Type</label>
                    <select name="components[${index}][diagram_type]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="mermaid">Mermaid Diagram</option>
                        <option value="custom">Custom Diagram</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="components[${index}][description]" required rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Responsibility</label>
                <textarea name="components[${index}][responsibility]" required rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Interfaces</label>
                <textarea name="components[${index}][interfaces]" rows="2"
                          placeholder="List interfaces exposed by this component..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="removeComponent(this)" 
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                    🗑️ Remove Component
                </button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function removeComponent(button) {
    button.closest('.component-item').remove();
}

function convertTextToDiagram() {
    const text = document.getElementById('text-description').value;
    const type = document.getElementById('diagram-type').value;

    if (!text.trim()) {
        alert('Please enter text to convert');
        return;
    }

    // Call API to convert text to diagram
    fetch('/api/documentation/text-to-diagram', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            text: text,
            diagram_type: type
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Display the generated diagram
            const render = document.getElementById('mermaid-render');
            render.innerHTML = `<div class="mermaid">${data.diagram_content}</div>`;
            
            // Store for saving
            tempDiagrams = [{
                name: `${type.charAt(0).toUpperCase() + type.slice(1)} Diagram`,
                type: type,
                content: data.diagram_content
            }];
            
            // Show preview
            document.getElementById('mermaid-preview').classList.remove('hidden');
            
            // Re-render mermaid
            mermaid.contentLoaded();
        }
    })
    .catch(error => console.error('Error:', error));
}

function previewManualDiagram() {
    const code = document.getElementById('manual-diagram-code').value;

    if (!code.trim()) {
        alert('Please enter Mermaid code');
        return;
    }

    const render = document.getElementById('manual-render');
    render.innerHTML = `<div class="mermaid">${code}</div>`;
    document.getElementById('manual-preview').classList.remove('hidden');
    
    // Re-render mermaid
    mermaid.contentLoaded();
}

function saveMermaidDiagram() {
    if (tempDiagrams.length === 0) return;
    
    const diagram = tempDiagrams[0];
    saveDiagramToForm(diagram.name, diagram.type, diagram.content);
}

function saveManualDiagram() {
    const name = document.getElementById('manual-diagram-name').value;
    const code = document.getElementById('manual-diagram-code').value;

    if (!name.trim() || !code.trim()) {
        alert('Please fill in all fields');
        return;
    }

    saveDiagramToForm(name, 'mermaid', code);
}

function saveDiagramToForm(name, type, content) {
    const listContainer = document.getElementById('diagrams-list');
    const html = `
        <div class="diagram-container border-l-4 border-purple-500" data-diagram-index="${diagramCounter}">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h4 class="font-bold text-gray-900">${name}</h4>
                    <p class="text-sm text-gray-600">Type: ${type}</p>
                </div>
                <button type="button" onclick="removeDiagram(this, ${diagramCounter})" 
                        class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                    ✕ Remove
                </button>
            </div>
            <input type="hidden" name="diagrams[${diagramCounter}][diagram_name]" value="${name}">
            <input type="hidden" name="diagrams[${diagramCounter}][diagram_type]" value="${type}">
            <input type="hidden" name="diagrams[${diagramCounter}][diagram_content]" value="${content.replace(/"/g, '&quot;')}">
            <div class="diagram-preview">
                <div class="mermaid">${content}</div>
            </div>
        </div>
    `;
    
    // Insert before the message if no diagrams exist
    if (listContainer.querySelector('.text-gray-500')) {
        listContainer.querySelector('.text-gray-500').remove();
    }
    
    listContainer.insertAdjacentHTML('beforeend', html);
    diagramCounter++;
    
    // Re-render mermaid
    mermaid.contentLoaded();
    
    // Clear inputs
    document.getElementById('text-description').value = '';
    document.getElementById('manual-diagram-name').value = '';
    document.getElementById('manual-diagram-code').value = '';
    document.getElementById('mermaid-preview').classList.add('hidden');
    document.getElementById('manual-preview').classList.add('hidden');
}

function removeDiagram(button, index) {
    button.closest('.diagram-container').remove();
}

// Initialize Mermaid
mermaid.initialize({ startOnLoad: true, theme: 'default' });
</script>
        </div>
    </div>
</x-app-layout>
