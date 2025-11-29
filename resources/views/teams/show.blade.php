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
                                            @if($member->id !== Auth::id() && $member->pivot->role !== 'Owner')
                                                <div class="flex items-center gap-2">
                                                    <form action="{{ route('teams.assignRole', [$team, $member]) }}" method="POST" class="inline-flex items-center gap-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="role" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            <option value="Member" {{ $member->pivot->role === 'Member' ? 'selected' : '' }}>Member</option>
                                                            <option value="Admin" {{ $member->pivot->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                                                        </select>
                                                        @if(isset($roles) && $roles->count())
                                                            <select name="role_id" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-md shadow-sm">
                                                                <option value="">-- Role --</option>
                                                                @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}" {{ ($member->pivot->role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </form>
                                                    <form action="{{ route('teams.removeMember', [$team, $member]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this member?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Remove member">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
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
                    <form method="POST" action="{{ route('teams.invite', $team) }}" class="flex gap-4" id="inviteForm">
                        @csrf
                        <div class="flex-grow relative">
                            <x-input-label for="userSearch" :value="__('Search by name or email')" class="sr-only" />
                            <x-text-input id="userSearch" class="block w-full" type="text" placeholder="Search users by name or email..." autocomplete="off" />
                            <input type="hidden" name="email" id="selectedEmail" required />
                            
                            <!-- Search Results Dropdown -->
                            <div id="searchResults" class="absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 hidden max-h-60 overflow-y-auto">
                            </div>
                            
                            <!-- Selected User Badge -->
                            <div id="selectedUser" class="hidden mt-2 inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                <span id="selectedUserName"></span>
                                <button type="button" onclick="clearSelection()" class="ml-2 text-indigo-600 hover:text-indigo-800">&times;</button>
                            </div>
                            
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <button type="submit" id="inviteBtn" disabled class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ __('Invite') }}
                        </button>
                    </form>
                </div>
            </div>
            
            <script>
                const searchInput = document.getElementById('userSearch');
                const searchResults = document.getElementById('searchResults');
                const selectedEmail = document.getElementById('selectedEmail');
                const selectedUser = document.getElementById('selectedUser');
                const selectedUserName = document.getElementById('selectedUserName');
                const inviteBtn = document.getElementById('inviteBtn');
                
                // Existing member IDs to exclude from search
                const existingMembers = @json($team->members->pluck('id'));
                
                let searchTimeout;
                
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    
                    if (query.length < 2) {
                        searchResults.classList.add('hidden');
                        return;
                    }
                    
                    searchTimeout = setTimeout(() => {
                        fetch(`/api/users/search?q=${encodeURIComponent(query)}&exclude[]=${existingMembers.join('&exclude[]=')}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            credentials: 'same-origin'
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.users && data.users.length > 0) {
                                searchResults.innerHTML = data.users.map(user => `
                                    <div class="px-4 py-2 hover:bg-indigo-50 cursor-pointer flex items-center justify-between" onclick="selectUser('${user.email}', '${user.name}')">
                                        <div>
                                            <div class="font-medium text-gray-900">${user.name}</div>
                                            <div class="text-sm text-gray-500">${user.email}</div>
                                        </div>
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                `).join('');
                                searchResults.classList.remove('hidden');
                            } else {
                                searchResults.innerHTML = '<div class="px-4 py-2 text-gray-500">No users found</div>';
                                searchResults.classList.remove('hidden');
                            }
                        })
                        .catch(() => {
                            searchResults.innerHTML = '<div class="px-4 py-2 text-red-500">Search failed</div>';
                            searchResults.classList.remove('hidden');
                        });
                    }, 300);
                });
                
                function selectUser(email, name) {
                    selectedEmail.value = email;
                    selectedUserName.textContent = `${name} (${email})`;
                    selectedUser.classList.remove('hidden');
                    searchInput.value = '';
                    searchInput.classList.add('hidden');
                    searchResults.classList.add('hidden');
                    inviteBtn.disabled = false;
                }
                
                function clearSelection() {
                    selectedEmail.value = '';
                    selectedUser.classList.add('hidden');
                    searchInput.classList.remove('hidden');
                    searchInput.focus();
                    inviteBtn.disabled = true;
                }
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.classList.add('hidden');
                    }
                });
            </script>
            @endcan

            @can('update', $team)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Invitations</h3>
                    @if(isset($pendingInvitations) && $pendingInvitations->count())
                        <ul class="space-y-2">
                            @foreach($pendingInvitations as $inv)
                                <li class="flex items-center justify-between border border-gray-200 rounded px-4 py-2">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $inv->email }}</div>
                                        <div class="text-sm text-gray-500">Invited by {{ $inv->inviter ? $inv->inviter->name : 'Unknown' }} — {{ $inv->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('teams.invitations.cancel', [$team, $inv]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm">Cancel</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-sm text-gray-500">No pending invitations.</div>
                    @endif
                </div>
            </div>
            @endcan

        </div>
    </div>
</x-app-layout>
