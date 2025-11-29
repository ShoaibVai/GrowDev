@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <h1 class="text-xl font-bold mb-4">{{ $team->name }} — Roles</h1>

    <form method="POST" action="{{ route('teams.roles.store', $team) }}" class="mb-4">
        @csrf
        <div class="grid grid-cols-3 gap-3">
            <input type="text" name="name" placeholder="Role name" class="col-span-1 px-3 py-2 border rounded" required />
            <input type="text" name="description" placeholder="Description" class="col-span-1 px-3 py-2 border rounded" />
            <button class="col-span-1 px-3 py-2 bg-indigo-600 text-white rounded">Create</button>
        </div>
    </form>

    <ul class="space-y-2">
        @foreach($roles as $role)
            <li class="flex items-center justify-between border rounded p-3">
                <div>
                    <div class="font-semibold">{{ $role->name }}</div>
                    <div class="text-sm text-gray-500">{{ $role->description }}</div>
                </div>
                <form method="POST" action="{{ route('teams.roles.destroy', [$team, $role]) }}">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 bg-red-100 text-red-700 rounded">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection
