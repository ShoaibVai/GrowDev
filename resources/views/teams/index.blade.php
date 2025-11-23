<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Teams') }}
            </h2>
            <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create Team') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Owned Teams -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Teams You Manage</h3>
                @if($ownedTeams->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($ownedTeams as $team)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="p-2 bg-indigo-100 rounded-lg">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full">Owner</span>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $team->name }}</h4>
                                    <p class="text-gray-500 text-sm mb-4">{{ $team->members->count() }} members</p>
                                    <div class="flex justify-end">
                                        <a href="{{ route('teams.show', $team) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Manage Team &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                        You don't manage any teams yet.
                    </div>
                @endif
            </div>

            <!-- Member Teams -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Teams You Belong To</h3>
                @if($teams->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($teams as $team)
                            @if($team->owner_id !== Auth::id())
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="p-2 bg-gray-100 rounded-lg">
                                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-50 rounded-full">Member</span>
                                        </div>
                                        <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $team->name }}</h4>
                                        <p class="text-gray-500 text-sm mb-4">{{ $team->members->count() }} members</p>
                                        <div class="flex justify-end">
                                            <a href="{{ route('teams.show', $team) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View Team &rarr;</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                        You are not a member of any other teams.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
