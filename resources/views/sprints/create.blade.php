<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Create Sprint</h2>
    </x-slot>
    <div class="max-w-lg">
        <form action="{{ route('sprints.store', $project) }}" method="POST" class="gd-card p-5 space-y-4">
            @csrf
            <div><label class="gd-label" for="name">Sprint Name</label>
            <input type="text" name="name" id="name" required class="gd-input text-[13px]" value="{{ old('name') }}" placeholder="Sprint 1"></div>
            <div><label class="gd-label" for="goal">Goal</label>
            <textarea name="goal" id="goal" rows="2" class="gd-textarea text-[13px]" placeholder="What should this sprint achieve?">{{ old('goal') }}</textarea></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="gd-label" for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" required class="gd-input text-[13px]" value="{{ old('start_date') }}"></div>
                <div><label class="gd-label" for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" required class="gd-input text-[13px]" value="{{ old('end_date') }}"></div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('sprints.index', $project) }}" class="gd-btn gd-btn-secondary">Cancel</a>
                <button type="submit" class="gd-btn gd-btn-primary">Create Sprint</button>
            </div>
        </form>
    </div>
</x-app-layout>
