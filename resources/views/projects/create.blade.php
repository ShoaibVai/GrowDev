<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">New Project</h2>
    </x-slot>
    <div class="max-w-lg">
        <form action="{{ route('projects.store') }}" method="POST" class="gd-card p-5 space-y-4">
            @csrf
            <div><label class="gd-label" for="name">Project Name</label>
            <input type="text" name="name" id="name" required class="gd-input text-[13px]" value="{{ old('name') }}" placeholder="e.g. Customer Portal"></div>
            <div><label class="gd-label" for="description">Description</label>
            <textarea name="description" id="description" rows="3" class="gd-textarea text-[13px]" placeholder="Brief project overview...">{{ old('description') }}</textarea></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="gd-label" for="status">Status</label>
                <select name="status" id="status" class="gd-select text-[13px]">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select></div>
                <div><label class="gd-label" for="type">Type</label>
                <select name="type" id="type" class="gd-select text-[13px]" onchange="document.getElementById('teamField').classList.toggle('hidden', this.value !== 'team')">
                    <option value="solo" {{ old('type', 'solo') === 'solo' ? 'selected' : '' }}>Solo</option>
                    <option value="team" {{ old('type') === 'team' ? 'selected' : '' }}>Team</option>
                </select></div>
            </div>
            <div id="teamField" class="{{ old('type') === 'team' ? '' : 'hidden' }}"><label class="gd-label" for="team_id">Team</label>
            <select name="team_id" id="team_id" class="gd-select text-[13px]">
                <option value="">Select team...</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                @endforeach
            </select></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="gd-label" for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="gd-input text-[13px]" value="{{ old('start_date') }}"></div>
                <div><label class="gd-label" for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="gd-input text-[13px]" value="{{ old('end_date') }}"></div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('dashboard') }}" class="gd-btn gd-btn-secondary">Cancel</a>
                <button type="submit" class="gd-btn gd-btn-primary">Create Project</button>
            </div>
        </form>
    </div>
</x-app-layout>
