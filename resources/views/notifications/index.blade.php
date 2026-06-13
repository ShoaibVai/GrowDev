<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Notifications</h2>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                    @csrf
                    <button class="gd-btn gd-btn-secondary gd-btn-sm">Mark All Read</button>
                </form>
                <form method="POST" action="{{ route('notifications.destroy-all') }}" class="inline">
                    @csrf @method('DELETE')
                    <button class="gd-btn gd-btn-danger gd-btn-sm">Clear All</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="gd-card overflow-hidden">
        @if($notifications->count())
            <div class="divide-y" style="border-color:var(--color-border)">
                @foreach($notifications as $n)
                    @php $d = $n->data; $type = $d['type'] ?? 'default'; @endphp
                    <div class="px-5 py-4 flex items-start gap-4 hover:bg-gd-surface-3 transition-colors duration-120"
                         style="{{ $n->read_at ? '' : 'border-left:3px solid var(--color-accent)' }}">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center flex-shrink-0 mt-0.5"
                             style="background:color-mix(in srgb, var(--color-{{ $type === 'task_status_change_requested' ? 'warning' : ($type === 'task_assigned' ? 'accent' : ($type === 'task_status_request_reviewed' ? 'success' : 'purple')) }}), 12%, transparent)">
                            <svg class="h-4 w-4" style="color:var(--color-{{ $type === 'task_status_change_requested' ? 'warning' : ($type === 'task_assigned' ? 'accent' : ($type === 'task_status_request_reviewed' ? 'success' : 'purple')) }})" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('notifications.read', $n->id) }}" class="text-[13px] font-medium hover:underline" style="color:var(--color-text)">
                                {{ $d['title'] ?? ($d['task_title'] ?? 'Notification') }}
                            </a>
                            <p class="text-[12px] mt-0.5" style="color:var(--color-text-muted)">{{ $d['message'] ?? '' }}</p>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-[11px]" style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $n->created_at->diffForHumans() }}</span>
                                @if(!$n->read_at)
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:var(--color-accent)"></span>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('notifications.destroy', $n->id) }}" method="POST" class="flex-shrink-0">
                            @csrf @method('DELETE')
                            <button class="gd-btn gd-btn-ghost gd-btn-icon-sm" style="color:var(--color-text-faint)" title="Delete">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="p-4" style="border-top:1px solid var(--color-border)">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-[14px] font-medium mb-2" style="color:var(--color-text)">No notifications</p>
                <p class="text-[13px]" style="color:var(--color-text-muted)">You're all caught up.</p>
            </div>
        @endif
    </div>
</x-app-layout>
