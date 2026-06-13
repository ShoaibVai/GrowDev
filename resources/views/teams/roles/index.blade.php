<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <h1 class="text-xl font-bold mb-4" style="font-family:var(--font-mono);color:var(--color-text);">{{ $team->name }} — Roles</h1>

        <form method="POST" action="{{ route('teams.roles.store', $team) }}" class="mb-4">
            @csrf
            <div class="grid grid-cols-3 gap-3">
                <input type="text" name="name" placeholder="Role name" class="col-span-1 px-3 py-2 rounded" style="background-color:var(--color-surface-2);border:1px solid var(--color-border);color:var(--color-text);" required />
                <input type="text" name="description" placeholder="Description" class="col-span-1 px-3 py-2 rounded" style="background-color:var(--color-surface-2);border:1px solid var(--color-border);color:var(--color-text);" />
                <button class="col-span-1 px-3 py-2 rounded" style="background-color:var(--color-accent);color:#fff;">Create</button>
            </div>
        </form>

        <ul class="space-y-2">
            @foreach($roles as $role)
                <li class="flex items-center justify-between rounded p-3" style="border:1px solid var(--color-border);">
                    <div>
                        <div class="font-semibold" style="color:var(--color-text);">{{ $role->name }}</div>
                        <div class="text-sm" style="color:var(--color-text-muted);">{{ $role->description }}</div>
                    </div>
                    <form method="POST" action="{{ route('teams.roles.destroy', [$team, $role]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 rounded" style="background-color:color-mix(in srgb, var(--color-danger) 15%, transparent);color:var(--color-danger);">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
