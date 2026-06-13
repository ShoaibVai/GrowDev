<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Teams</h2>
            <a href="{{ route('teams.create') }}" class="gd-btn gd-btn-primary gd-btn-sm">
                <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Team
            </a>
        </div>
    </x-slot>

    @if($pendingInvitations->count())
    <div class="gd-card p-4 mb-6" style="border-left:3px solid var(--color-accent)">
        <p class="text-[12px] font-semibold uppercase tracking-wider mb-3" style="color:var(--color-text-muted)">Pending Invitations</p>
        <div class="space-y-2">
            @foreach($pendingInvitations as $inv)
                <div class="flex items-center justify-between text-[13px]">
                    <span>
                        <span style="color:var(--color-text);font-weight:500">{{ $inv->team->name }}</span>
                        <span class="text-[11px] ml-2" style="font-family:var(--font-mono);color:var(--color-text-faint)">invited by {{ $inv->inviter->name }}</span>
                    </span>
                    <div class="flex gap-2">
                        <a href="{{ route('invitations.accept', $inv->token) }}" class="gd-btn gd-btn-primary gd-btn-sm">Accept</a>
                        <a href="{{ route('invitations.decline', $inv->token) }}" class="gd-btn gd-btn-secondary gd-btn-sm">Decline</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 stagger">
        @foreach($ownedTeams as $team)
            <a href="{{ route('teams.show', $team) }}" class="gd-card gd-card-interactive p-5 block">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="gd-chip text-[10px]">TEAM</span>
                            <span class="text-[11px]" style="color:var(--color-text-muted)">Owner</span>
                        </div>
                        <p class="text-[16px] font-semibold" style="font-family:var(--font-mono);color:var(--color-text)">{{ $team->name }}</p>
                    </div>
                    <span class="gd-badge gd-badge-accent">Owned</span>
                </div>
                <p class="text-[12px] mt-3" style="color:var(--color-text-muted)">{{ $team->members_count ?? $team->members()->count() }} members</p>
            </a>
        @endforeach
        @foreach($teams->whereNotIn('id', $ownedTeams->pluck('id')) as $team)
            <a href="{{ route('teams.show', $team) }}" class="gd-card gd-card-interactive p-5 block">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="gd-chip text-[10px]">TEAM</span>
                            <span class="text-[11px]" style="color:var(--color-text-muted)">Member</span>
                        </div>
                        <p class="text-[16px] font-semibold" style="font-family:var(--font-mono);color:var(--color-text)">{{ $team->name }}</p>
                    </div>
                    <span class="gd-badge gd-badge-purple">Joined</span>
                </div>
                <p class="text-[12px] mt-3" style="color:var(--color-text-muted)">{{ $team->members()->count() }} members</p>
            </a>
        @endforeach
    </div>

    @if($teams->isEmpty() && $ownedTeams->isEmpty())
        <div class="gd-card p-12 text-center">
            <p class="text-[14px] font-medium mb-2" style="color:var(--color-text)">No teams yet</p>
            <p class="text-[13px] mb-4" style="color:var(--color-text-muted)">Create or join a team to collaborate on projects.</p>
            <a href="{{ route('teams.create') }}" class="gd-btn gd-btn-primary">Create Team</a>
        </div>
    @endif
</x-app-layout>
