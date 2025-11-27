<div class="requirement-item p-4 bg-purple-50 rounded-lg border border-purple-200 {{ $req->parent_id ? 'ml-8 border-l-4 border-l-purple-300' : '' }}" data-index="{{ $index }}">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Section #</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][section_number]" 
                   value="{{ $req->section_number ?? '' }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 font-mono text-purple-600 font-bold">
            <input type="hidden" name="non_functional_requirements[{{ $index }}][parent_section]" 
                   value="{{ $req->parent ? $req->parent->section_number : '' }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Requirement ID</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][requirement_id]" 
                   value="{{ $req->requirement_id }}" required
                   placeholder="NFR-001"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][title]" 
                   value="{{ $req->title }}" required
                   placeholder="Requirement Title"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="non_functional_requirements[{{ $index }}][category]" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected($req->category === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="non_functional_requirements[{{ $index }}][priority]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                    <option value="low" @selected($req->priority === 'low')>Low</option>
                    <option value="medium" @selected($req->priority === 'medium')>Medium</option>
                    <option value="high" @selected($req->priority === 'high')>High</option>
                    <option value="critical" @selected($req->priority === 'critical')>Critical</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="non_functional_requirements[{{ $index }}][status]" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
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
            <textarea name="non_functional_requirements[{{ $index }}][description]" required rows="2"
                      placeholder="Describe the non-functional requirement..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ $req->description }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Acceptance Criteria</label>
            <textarea name="non_functional_requirements[{{ $index }}][acceptance_criteria]" rows="2"
                      placeholder="How will this be verified?"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ $req->acceptance_criteria ?? '' }}</textarea>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Measurement Method</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][measurement]" 
                   value="{{ $req->measurement ?? '' }}"
                   placeholder="How to measure (e.g., load test)"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Target Value</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][target_value]" 
                   value="{{ $req->target_value ?? '' }}"
                   placeholder="e.g., < 2 seconds, 99.9% uptime"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Source/Stakeholder</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][source]" 
                   value="{{ $req->source ?? '' }}"
                   placeholder="Who requested this?"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
        </div>
    </div>

    <div class="children-container space-y-4 mt-4">
        {{-- Recursive children rendering would go here if needed --}}
    </div>

    <div class="flex justify-between items-center mt-4 pt-4 border-t border-purple-200">
        <button type="button" onclick="addSubRequirement(this, 'non_functional')" 
                class="text-sm px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition">
            + Add Sub-Requirement
        </button>
        <button type="button" onclick="removeRequirement(this)" 
                class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
            🗑️ Remove
        </button>
    </div>
</div>
