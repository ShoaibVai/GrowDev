<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Edit Sprint</h2>
    </x-slot>
    <div class="max-w-lg">
        <form action="{{ route('sprints.update', [$project, $sprint]) }}" method="POST" class="gd-card p-5 space-y-4">
            @csrf @method('PUT')
            <div><label class="gd-label" for="name">Sprint Name</label>
            <input type="text" name="name" id="name" required class="gd-input text-[13px]" value="{{ old('name', $sprint->name) }}"></div>
            <div><label class="gd-label" for="goal">Goal</label>
            <textarea name="goal" id="goal" rows="2" class="gd-textarea text-[13px]">{{ old('goal', $sprint->goal) }}</textarea></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="gd-label" for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" required class="gd-input text-[13px]" value="{{ old('start_date', $sprint->start_date->format('Y-m-d')) }}"></div>
                <div><label class="gd-label" for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" required class="gd-input text-[13px]" value="{{ old('end_date', $sprint->end_date->format('Y-m-d')) }}"></div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('sprints.show', [$project, $sprint]) }}" class="gd-btn gd-btn-secondary">Cancel</a>
                <button type="submit" class="gd-btn gd-btn-primary">Update Sprint</button>
            </div>
        </form>
    </div>
</x-app-layout>
