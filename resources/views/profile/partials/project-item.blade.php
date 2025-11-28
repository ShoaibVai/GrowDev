<div class="form-item p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-gray-800">{{ __('Project') }} #{{ $index + 1 }}</h4>
        <button type="button" onclick="removeElement(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
            {{ __('Remove') }}
        </button>
    </div>

    <input type="hidden" name="projects_manual[{{ $index }}][id]" value="{{ old("projects_manual.$index.id", $project->id ?? '') }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Project Name') }} *</label>
            <input type="text" name="projects_manual[{{ $index }}][name]"
                   value="{{ old("projects_manual.$index.name", $project->name ?? '') }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }} *</label>
            <select name="projects_manual[{{ $index }}][status]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach(['active' => 'Active', 'on_hold' => 'On Hold', 'completed' => 'Completed'] as $value => $label)
                    <option value="{{ $value }}" @selected(old("projects_manual.$index.status", $project->status ?? 'active') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Type') }} *</label>
            <select name="projects_manual[{{ $index }}][type]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach(['solo' => 'Solo', 'team' => 'Team'] as $value => $label)
                    <option value="{{ $value }}" @selected(old("projects_manual.$index.type", $project->type ?? 'solo') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }}</label>
            <input type="date" name="projects_manual[{{ $index }}][start_date]"
                   value="{{ old("projects_manual.$index.start_date", isset($project->start_date) ? optional($project->start_date)->format('Y-m-d') : '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('End Date') }}</label>
            <input type="date" name="projects_manual[{{ $index }}][end_date]"
                   value="{{ old("projects_manual.$index.end_date", isset($project->end_date) ? optional($project->end_date)->format('Y-m-d') : '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
            <textarea name="projects_manual[{{ $index }}][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old("projects_manual.$index.description", $project->description ?? '') }}</textarea>
        </div>
    </div>
</div>
