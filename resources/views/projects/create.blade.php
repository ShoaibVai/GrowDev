<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf

                        <!-- Project Name -->
                        <div>
                            <x-input-label for="name" :value="__('Project Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" 
                                      class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" required
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Project Type -->
                        <div class="mt-4">
                            <x-input-label for="type" :value="__('Project Type')" />
                            <div class="flex gap-4 items-center mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="solo" class="form-radio" {{ old('type', 'solo') === 'solo' ? 'checked' : '' }} />
                                    <span class="ml-2">Solo</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="team" class="form-radio" {{ old('type') === 'team' ? 'checked' : '' }} />
                                    <span class="ml-2">Team</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Team selection (if team) -->
                        <div class="mt-4" id="teamSelect" style="display: {{ old('type') === 'team' ? 'block' : 'none' }};">
                            <x-input-label for="team_id" :value="__('Assign to Team')" />
                            <div class="flex gap-2">
                                <select id="team_id" name="team_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="">Select a team</option>
                                    @if(isset($teams) && $teams->count())
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if(!(isset($teams) && $teams->count()))
                                    <a href="{{ route('teams.create') }}" class="inline-flex items-center px-3 py-2 bg-green-500 text-white rounded-md">Create Team</a>
                                @endif
                            </div>
                        </div>

                        <!-- Start/End Dates -->
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="start_date" :value="__('Start Date')" />
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('End Date')" />
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" />
                            </div>
                        </div>
                    </form>

                    <script>
                        // Simple JS to toggle team select when type set to 'team'
                        const radios = document.querySelectorAll('input[name="type"]');
                        const teamSelect = document.getElementById('teamSelect');
                        radios.forEach(r => r.addEventListener('change', function(){
                            if (this.value === 'team') {
                                teamSelect.style.display = 'block';
                            } else {
                                teamSelect.style.display = 'none';
                            }
                        }));
                    </script>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>

                            <x-primary-button>
                                {{ __('Create Project') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
