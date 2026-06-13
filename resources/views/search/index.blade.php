<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Search: "{{ $q }}"</h2>
    </x-slot>

    <div class="space-y-6">
        @if($projects->count())
        <div class="gd-card p-0 overflow-hidden">
            <div class="px-5 py-3" style="border-bottom:1px solid var(--color-border)">
                <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Projects ({{ $projects->count() }})</p>
            </div>
            <div class="divide-y" style="border-color:var(--color-border)">
                @foreach($projects as $p)
                    <a href="{{ route('projects.show', $p) }}" class="block px-5 py-3 hover:bg-gd-surface-3 transition-colors duration-120">
                        <span class="gd-chip text-[10px] mr-2">P-{{ $p->id }}</span>
                        <span class="text-[13px] font-medium" style="color:var(--color-text)">{{ $p->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($tasks->count())
        <div class="gd-card p-0 overflow-hidden">
            <div class="px-5 py-3" style="border-bottom:1px solid var(--color-border)">
                <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Tasks ({{ $tasks->count() }})</p>
            </div>
            <div class="divide-y" style="border-color:var(--color-border)">
                @foreach($tasks as $t)
                    <a href="{{ route('tasks.show', $t) }}" class="block px-5 py-3 hover:bg-gd-surface-3 transition-colors duration-120">
                        <span class="gd-chip text-[10px] mr-2">T-{{ $t->id }}</span>
                        <span class="text-[13px] font-medium" style="color:var(--color-text)">{{ $t->title }}</span>
                        <span class="text-[11px] ml-2" style="color:var(--color-text-muted)">{{ $t->project->name ?? '' }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($teams->count())
        <div class="gd-card p-0 overflow-hidden">
            <div class="px-5 py-3" style="border-bottom:1px solid var(--color-border)">
                <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Teams ({{ $teams->count() }})</p>
            </div>
            <div class="divide-y" style="border-color:var(--color-border)">
                @foreach($teams as $t)
                    <a href="{{ route('teams.show', $t) }}" class="block px-5 py-3 hover:bg-gd-surface-3 transition-colors duration-120">
                        <span class="gd-chip text-[10px] mr-2">TEAM</span>
                        <span class="text-[13px] font-medium" style="color:var(--color-text)">{{ $t->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($srsDocs->count())
        <div class="gd-card p-0 overflow-hidden">
            <div class="px-5 py-3" style="border-bottom:1px solid var(--color-border)">
                <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Documents ({{ $srsDocs->count() }})</p>
            </div>
            <div class="divide-y" style="border-color:var(--color-border)">
                @foreach($srsDocs as $d)
                    <a href="{{ route('documentation.srs.edit', $d) }}" class="block px-5 py-3 hover:bg-gd-surface-3 transition-colors duration-120">
                        <span class="text-[13px] font-medium" style="color:var(--color-text)">{{ $d->title }}</span>
                        <span class="text-[11px] ml-2" style="color:var(--color-text-muted)">{{ $d->project->name ?? '' }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($projects->isEmpty() && $tasks->isEmpty() && $teams->isEmpty() && $srsDocs->isEmpty())
            <div class="gd-card p-12 text-center">
                <p class="text-[14px] font-medium mb-2" style="color:var(--color-text)">No results found</p>
                <p class="text-[13px]" style="color:var(--color-text-muted)">Try a different search term.</p>
            </div>
        @endif
    </div>
</x-app-layout>
