<div class="requirement-item" style="padding: 1rem; background-color: color-mix(in srgb, var(--color-purple) 10%, var(--color-surface-2)); border-radius: 0.5rem; border: 1px solid color-mix(in srgb, var(--color-purple) 30%, transparent); {{ $req->parent_id ? 'margin-left: 2rem; border-left: 4px solid color-mix(in srgb, var(--color-purple) 50%, transparent);' : '' }}" data-index="{{ $index }}">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Section #</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][section_number]" 
                   value="{{ $req->section_number ?? '' }}" required
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-purple); font-family: var(--font-mono); font-weight: 700;">
            <input type="hidden" name="non_functional_requirements[{{ $index }}][parent_section]" 
                   value="{{ $req->parent ? $req->parent->section_number : '' }}">
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Requirement ID</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][requirement_id]" 
                   value="{{ $req->requirement_id }}" required
                   placeholder="NFR-001"
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Title</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][title]" 
                   value="{{ $req->title }}" required
                   placeholder="Requirement Title"
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Category</label>
            <select name="non_functional_requirements[{{ $index }}][category]" required
                    class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected($req->category === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Priority</label>
                <select name="non_functional_requirements[{{ $index }}][priority]" required
                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
                    <option value="low" @selected($req->priority === 'low')>Low</option>
                    <option value="medium" @selected($req->priority === 'medium')>Medium</option>
                    <option value="high" @selected($req->priority === 'high')>High</option>
                    <option value="critical" @selected($req->priority === 'critical')>Critical</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Status</label>
                <select name="non_functional_requirements[{{ $index }}][status]" 
                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
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
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Description</label>
            <textarea name="non_functional_requirements[{{ $index }}][description]" required rows="2"
                      placeholder="Describe the non-functional requirement..."
                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">{{ $req->description }}</textarea>
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Acceptance Criteria</label>
            <textarea name="non_functional_requirements[{{ $index }}][acceptance_criteria]" rows="2"
                      placeholder="How will this be verified?"
                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">{{ $req->acceptance_criteria ?? '' }}</textarea>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Measurement Method</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][measurement]" 
                   value="{{ $req->measurement ?? '' }}"
                   placeholder="How to measure (e.g., load test)"
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Target Value</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][target_value]" 
                   value="{{ $req->target_value ?? '' }}"
                   placeholder="e.g., &lt; 2 seconds, 99.9% uptime"
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Source/Stakeholder</label>
            <input type="text" name="non_functional_requirements[{{ $index }}][source]" 
                   value="{{ $req->source ?? '' }}"
                   placeholder="Who requested this?"
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Assigned Roles</label>
            @if(isset($roles) && $roles->count())
                <select name="non_functional_requirements[{{ $index }}][roles][]" multiple class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
                    @foreach($roles as $role)
                        @php $selectedRoles = $req->roleMappings->pluck('role_id')->toArray() ?? []; @endphp
                        <option value="{{ $role->id }}" @selected(in_array($role->id, $selectedRoles))>{{ $role->name }}</option>
                    @endforeach
                </select>
            @else
                <div style="font-size: 0.75rem; color: var(--color-text-muted);">No roles available for this project.</div>
            @endif
        </div>
    </div>

    <div class="children-container space-y-4 mt-4">
        {{-- Recursive children rendering would go here if needed --}}
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid color-mix(in srgb, var(--color-purple) 30%, transparent);">
        <button type="button" onclick="addSubRequirement(this, 'non_functional')" 
                class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                style="font-size: 0.875rem; padding: 0.25rem 0.75rem; background-color: color-mix(in srgb, var(--color-purple) 15%, transparent); color: var(--color-purple); border-radius: 0.25rem; transition: background-color 0.2s;"
                onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-purple) 25%, transparent)'"
                onmouseout="this.style.backgroundColor='color-mix(in srgb, var(--color-purple) 15%, transparent)'">
            + Add Sub-Requirement
        </button>
        <button type="button" onclick="removeRequirement(this)" 
                class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                style="font-size: 0.875rem; padding: 0.25rem 0.75rem; background-color: color-mix(in srgb, var(--color-danger) 15%, transparent); color: var(--color-danger); border-radius: 0.25rem; transition: background-color 0.2s;"
                onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-danger) 25%, transparent)'"
                onmouseout="this.style.backgroundColor='color-mix(in srgb, var(--color-danger) 15%, transparent)'">
            🗑️ Remove
        </button>
    </div>
</div>
