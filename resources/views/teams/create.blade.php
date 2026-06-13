<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Create Team</h2>
    </x-slot>

    <div class="max-w-lg">
        <form action="{{ route('teams.store') }}" method="POST" class="gd-card p-5 space-y-4">
            @csrf
            <div>
                <label class="gd-label" for="name">Team Name</label>
                <input type="text" name="name" id="name" required class="gd-input text-[13px]" value="{{ old('name') }}" placeholder="e.g. Backend Squad">
                @error('name') <p class="text-[12px] mt-1" style="color:var(--color-danger)">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('teams.index') }}" class="gd-btn gd-btn-secondary">Cancel</a>
                <button type="submit" class="gd-btn gd-btn-primary">Create Team</button>
            </div>
        </form>
    </div>
</x-app-layout>
