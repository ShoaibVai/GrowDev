<div class="form-item p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-sm" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Project') }} #{{ $index + 1 }}</h4>
        <button type="button" onclick="removeElement(this)" class="text-sm font-medium focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="color: var(--color-danger);">
            {{ __('Remove') }}
        </button>
    </div>

    <input type="hidden" name="projects_manual[{{ $index }}][id]" value="{{ old("projects_manual.$index.id", $project->id ?? '') }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Project Name') }} *</label>
            <input type="text" name="projects_manual[{{ $index }}][name]"
                   value="{{ old("projects_manual.$index.name", $project->name ?? '') }}"
                   required
                   class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                   style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Status') }} *</label>
            <select name="projects_manual[{{ $index }}][status]" required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                @foreach(['active' => 'Active', 'on_hold' => 'On Hold', 'completed' => 'Completed'] as $value => $label)
                    <option value="{{ $value }}" @selected(old("projects_manual.$index.status", $project->status ?? 'active') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Type') }} *</label>
            <select name="projects_manual[{{ $index }}][type]" required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                @foreach(['solo' => 'Solo', 'team' => 'Team'] as $value => $label)
                    <option value="{{ $value }}" @selected(old("projects_manual.$index.type", $project->type ?? 'solo') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Start Date') }}</label>
            <input type="date" name="projects_manual[{{ $index }}][start_date]"
                   value="{{ old("projects_manual.$index.start_date", isset($project->start_date) ? optional($project->start_date)->format('Y-m-d') : '') }}"
                   class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                   style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('End Date') }}</label>
            <input type="date" name="projects_manual[{{ $index }}][end_date]"
                   value="{{ old("projects_manual.$index.end_date", isset($project->end_date) ? optional($project->end_date)->format('Y-m-d') : '') }}"
                   class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                   style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Description') }}</label>
            <textarea name="projects_manual[{{ $index }}][description]" rows="3"
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">{{ old("projects_manual.$index.description", $project->description ?? '') }}</textarea>
        </div>
    </div>
</div>
