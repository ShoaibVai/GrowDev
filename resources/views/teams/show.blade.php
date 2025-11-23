<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $team->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Team Members -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Team Members</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    @can('update', $team)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($team->members as $member)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $member->pivot->role === 'Owner' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $member->pivot->role }}
                                            </span>
                                        </td>
                                        @can('update', $team)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($member->id !== Auth::id())
                                                <form action="{{ route('teams.assignRole', [$team, $member]) }}" method="POST" class="inline-flex items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="role" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <option value="Member" {{ $member->pivot->role === 'Member' ? 'selected' : '' }}>Member</option>
                                                        <option value="Admin" {{ $member->pivot->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                                                    </select>
                                                </form>
                                            @endif
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Invite Member -->
            @can('update', $team)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Invite Member</h3>
                    <form method="POST" action="{{ route('teams.invite', $team) }}" class="flex gap-4">
                        @csrf
                        <div class="flex-grow">
                            <x-input-label for="email" :value="__('Email Address')" class="sr-only" />
                            <x-text-input id="email" class="block w-full" type="email" name="email" placeholder="Enter email address" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Invite') }}
                        </button>
                    </form>
                </div>
            </div>
            @endcan

        </div>
    </div>
</x-app-layout>
