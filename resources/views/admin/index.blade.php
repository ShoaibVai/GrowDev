<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Administration</h2>
    </x-slot>
    <div class="gd-card p-5 space-y-4">
        <p class="text-[13px]" style="color:var(--color-text-muted)">Export data</p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.export', 'users') }}" class="gd-btn gd-btn-secondary gd-btn-sm">Export Users</a>
            <a href="{{ route('admin.export', 'projects') }}" class="gd-btn gd-btn-secondary gd-btn-sm">Export Projects</a>
            <a href="{{ route('admin.export', 'tasks') }}" class="gd-btn gd-btn-secondary gd-btn-sm">Export Tasks</a>
        </div>
    </div>
</x-app-layout>
