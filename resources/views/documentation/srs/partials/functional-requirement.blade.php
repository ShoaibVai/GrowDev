<div class="requirement-item p-4 bg-gray-50 rounded-lg border border-gray-200 {{ $req->parent_id ? 'ml-8 border-l-4 border-l-indigo-300' : '' }}" data-index="{{ $index }}">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Section #</label>
            <input type="text" name="functional_requirements[{{ $index }}][section_number]" 
                   value="{{ $req->section_number ?? '' }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono text-indigo-600 font-bold">
            <input type="hidden" name="functional_requirements[{{ $index }}][parent_section]" 
                   value="{{ $req->parent ? $req->parent->section_number : '' }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Requirement ID</label>
            <input type="text" name="functional_requirements[{{ $index }}][requirement_id]" 
                   value="{{ $req->requirement_id }}" required
                   placeholder="FR-001"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="functional_requirements[{{ $index }}][title]" 
                   value="{{ $req->title }}" required
                   placeholder="Requirement Title"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="functional_requirements[{{ $index }}][priority]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                    <option value="low" @selected($req->priority === 'low')>Low</option>
                    <option value="medium" @selected($req->priority === 'medium')>Medium</option>
                    <option value="high" @selected($req->priority === 'high')>High</option>
                    <option value="critical" @selected($req->priority === 'critical')>Critical</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="functional_requirements[{{ $index }}][status]" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                    <option value="draft" @selected(($req->status ?? 'draft') === 'draft')>Draft</option>
                    <option value="review" @selected(($req->status ?? 'draft') === 'review')>Review</option>
                    <option value="approved" @selected(($req->status ?? 'draft') === 'approved')>Approved</option>
                    <option value="implemented" @selected(($req->status ?? 'draft') === 'implemented')>Implemented</option>
                    <option value="verified" @selected(($req->status ?? 'draft') === 'verified')>Verified</option>
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="functional_requirements[{{ $index }}][description]" required rows="2"
                      placeholder="Describe the requirement in detail..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $req->description }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Acceptance Criteria</label>
            <textarea name="functional_requirements[{{ $index }}][acceptance_criteria]" rows="2"
                      placeholder="How will this requirement be verified?"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $req->acceptance_criteria ?? '' }}</textarea>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Source/Stakeholder</label>
            <input type="text" name="functional_requirements[{{ $index }}][source]" 
                   value="{{ $req->source ?? '' }}"
                   placeholder="Who requested this requirement?"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="block text-sm font-medium text-gray-700">🎨 UX Considerations</label>
                <button type="button" onclick="addUxItem(this, {{ $index }})" 
                        class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">+ Add</button>
            </div>
            <div class="ux-items-container space-y-1" data-index="{{ $index }}">
                @if ($req->ux_considerations && count($req->ux_considerations) > 0)
                    @foreach ($req->ux_considerations as $uxItem)
                        <div class="ux-item flex gap-2">
                            <input type="text" name="functional_requirements[{{ $index }}][ux_considerations][]" 
                                   value="{{ $uxItem }}"
                                   class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                            <button type="button" onclick="this.parentElement.remove()" 
                                    class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">✕</button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="children-container space-y-4 mt-4">
        {{-- Recursive children rendering would go here if needed --}}
    </div>

    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
        <button type="button" onclick="addSubRequirement(this, 'functional')" 
                class="text-sm px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition">
            + Add Sub-Requirement
        </button>
        <button type="button" onclick="removeRequirement(this)" 
                class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
            🗑️ Remove
        </button>
    </div>
</div>
