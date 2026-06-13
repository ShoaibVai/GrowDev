<div class="requirement-item" style="padding: 1rem; background-color: var(--color-surface-2); border-radius: 0.5rem; border: 1px solid var(--color-border); {{ $req->parent_id ? 'margin-left: 2rem; border-left: 4px solid color-mix(in srgb, var(--color-accent) 50%, transparent);' : '' }}" data-index="{{ $index }}">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Section #</label>
            <input type="text" name="functional_requirements[{{ $index }}][section_number]" 
                   value="{{ $req->section_number ?? '' }}" required
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-accent); font-family: var(--font-mono); font-weight: 700;">
            <input type="hidden" name="functional_requirements[{{ $index }}][parent_section]" 
                   value="{{ $req->parent ? $req->parent->section_number : '' }}">
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Requirement ID</label>
            <input type="text" name="functional_requirements[{{ $index }}][requirement_id]" 
                   value="{{ $req->requirement_id }}" required
                   placeholder="FR-001"
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Title</label>
            <input type="text" name="functional_requirements[{{ $index }}][title]" 
                   value="{{ $req->title }}" required
                   placeholder="Requirement Title"
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Priority</label>
                <select name="functional_requirements[{{ $index }}][priority]" required
                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
                    <option value="low" @selected($req->priority === 'low')>Low</option>
                    <option value="medium" @selected($req->priority === 'medium')>Medium</option>
                    <option value="high" @selected($req->priority === 'high')>High</option>
                    <option value="critical" @selected($req->priority === 'critical')>Critical</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Status</label>
                <select name="functional_requirements[{{ $index }}][status]" 
                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
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
            <textarea name="functional_requirements[{{ $index }}][description]" required rows="2"
                      placeholder="Describe the requirement in detail..."
                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">{{ $req->description }}</textarea>
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Assigned Roles</label>
            @if(isset($roles) && $roles->count())
                <select name="functional_requirements[{{ $index }}][roles][]" multiple class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
                    @foreach($roles as $role)
                        @php
                            $selectedRoles = $req->roleMappings->pluck('role_id')->toArray() ?? [];
                        @endphp
                        <option value="{{ $role->id }}" @selected(in_array($role->id, $selectedRoles))>{{ $role->name }}</option>
                    @endforeach
                </select>
            @else
                <div style="font-size: 0.75rem; color: var(--color-text-muted);">No roles available for this project.</div>
            @endif
        </div>
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Acceptance Criteria</label>
            <textarea name="functional_requirements[{{ $index }}][acceptance_criteria]" rows="2"
                      placeholder="How will this requirement be verified?"
                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">{{ $req->acceptance_criteria ?? '' }}</textarea>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Source/Stakeholder</label>
            <input type="text" name="functional_requirements[{{ $index }}][source]" 
                   value="{{ $req->source ?? '' }}"
                   placeholder="Who requested this requirement?"
                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
        </div>
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text);">🎨 UX Considerations</label>
                <button type="button" onclick="addUxItem(this, {{ $index }})" 
                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                        style="font-size: 0.75rem; padding: 0.25rem 0.5rem; background-color: var(--color-accent); color: white; border-radius: 0.25rem;">+ Add</button>
            </div>
            <div class="ux-items-container space-y-1" data-index="{{ $index }}">
                @if ($req->ux_considerations && count($req->ux_considerations) > 0)
                    @foreach ($req->ux_considerations as $uxItem)
                        <div class="ux-item flex gap-2">
                            <input type="text" name="functional_requirements[{{ $index }}][ux_considerations][]" 
                                   value="{{ $uxItem }}"
                                   class="flex-1 focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                   style="padding: 0.25rem 0.5rem; font-size: 0.875rem; border: 1px solid var(--color-border); border-radius: 0.25rem; background-color: var(--color-surface); color: var(--color-text);">
                            <button type="button" onclick="this.parentElement.remove()" 
                                    class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="padding: 0.25rem 0.5rem; background-color: var(--color-danger); color: white; border-radius: 0.25rem; font-size: 0.75rem;">✕</button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="children-container space-y-4 mt-4">
        {{-- Recursive children rendering would go here if needed --}}
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
        <button type="button" onclick="addSubRequirement(this, 'functional')" 
                class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="font-size: 0.875rem; padding: 0.25rem 0.75rem; background-color: color-mix(in srgb, var(--color-accent) 15%, transparent); color: var(--color-accent); border-radius: 0.25rem; transition: background-color 0.2s;"
                onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-accent) 25%, transparent)'"
                onmouseout="this.style.backgroundColor='color-mix(in srgb, var(--color-accent) 15%, transparent)'">
            + Add Sub-Requirement
        </button>
        <button type="button" onclick="removeRequirement(this)" 
                class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="font-size: 0.875rem; padding: 0.25rem 0.75rem; background-color: color-mix(in srgb, var(--color-danger) 15%, transparent); color: var(--color-danger); border-radius: 0.25rem; transition: background-color 0.2s;"
                onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-danger) 25%, transparent)'"
                onmouseout="this.style.backgroundColor='color-mix(in srgb, var(--color-danger) 15%, transparent)'">
            🗑️ Remove
        </button>
    </div>
</div>
