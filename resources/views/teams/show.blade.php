<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="gd-chip">TEAM</span>
                <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">{{ $team->name }}</h2>
            </div>
            <div class="flex gap-2">
                @can('delete', $team)
                    <form action="{{ route('teams.destroy', $team) }}" method="POST" onsubmit="return confirm('Delete team?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="gd-btn gd-btn-danger gd-btn-sm">Delete Team</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Members list --}}
        <div class="lg:col-span-2 gd-card p-0 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid var(--color-border)">
                <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Members ({{ $team->members->count() }})</p>
                @can('update', $team)
                <div class="flex gap-2">
                    <a href="{{ route('teams.roles.index', $team) }}" class="gd-btn gd-btn-secondary gd-btn-sm">Manage Roles</a>
                    <button type="button" onclick="document.getElementById('inviteForm').classList.toggle('hidden')" class="gd-btn gd-btn-primary gd-btn-sm">
                        <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Invite
                    </button>
                </div>
                @endcan
            </div>

            @can('update', $team)
            <div id="inviteForm" class="hidden px-5 py-4" style="border-bottom:1px solid var(--color-border);background:var(--color-surface-2)">
                <form action="{{ route('teams.invite', $team) }}" method="POST" class="flex items-end gap-2">
                    @csrf
                    <div class="flex-1">
                        <label class="gd-label">Email address</label>
                        <input type="email" name="email" required class="gd-input h-7 text-[12px]" placeholder="colleague@email.com">
                    </div>
                    <button type="submit" class="gd-btn gd-btn-primary gd-btn-sm">Send Invite</button>
                </form>
            </div>
            @endcan

            <div class="divide-y" style="border-color:var(--color-border)">
                @foreach($team->members as $member)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div class="flex items-center gap-3">
                            <span class="gd-avatar" style="font-size:12px">{{ substr($member->name, 0, 1) }}</span>
                            <div>
                                <p class="text-[13px] font-medium" style="color:var(--color-text)">{{ $member->name }}</p>
                                <p class="text-[11px]" style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $member->email }}</p>
                            </div>
                            @if($team->owner_id === $member->id)
                                <span class="gd-badge gd-badge-accent">Owner</span>
                            @endif
                        </div>
                        @can('update', $team)
                        <div class="flex items-center gap-2">
                            <form action="{{ route('teams.assignRole', [$team, $member]) }}" method="POST" class="flex items-center gap-1">
                                @csrf @method('PATCH')
                                <select name="role_id" class="gd-select h-7 text-[12px] w-32">
                                    <option value="">No role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="gd-btn gd-btn-ghost gd-btn-sm" style="color:var(--color-text-muted)">Set</button>
                            </form>
                            @if($team->owner_id !== $member->id)
                                <form action="{{ route('teams.removeMember', [$team, $member]) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="gd-btn gd-btn-ghost gd-btn-sm" style="color:var(--color-danger)">Remove</button>
                                </form>
                            @endif
                        </div>
                        @endcan
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pending invitations --}}
        <div class="space-y-6">
            <div class="gd-card p-5">
                <p class="text-[12px] font-semibold uppercase tracking-wider mb-3" style="color:var(--color-text-muted)">Pending Invitations</p>
                @forelse($pendingInvitations as $inv)
                    <div class="flex items-center justify-between py-2 text-[13px]" @if(!$loop->last) style="border-bottom:1px solid var(--color-border)" @endif>
                        <span style="color:var(--color-text)">{{ $inv->email ?? $inv->user->email }}</span>
                        <span class="text-[11px]" style="color:var(--color-text-muted)">Invited by {{ $inv->inviter->name }}</span>
                        <form action="{{ route('teams.invitations.cancel', [$team, $inv]) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-[11px] hover:underline" style="color:var(--color-danger)">Cancel</button>
                        </form>
                    </div>
                @empty
                    <p class="text-[13px] py-2" style="color:var(--color-text-muted)">No pending invitations</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
