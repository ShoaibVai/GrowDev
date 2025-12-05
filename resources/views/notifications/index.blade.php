<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if(Auth::user()->notifications->count() > 0)
                <div class="flex gap-2">
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                            @csrf
                            <x-secondary-button type="submit">
                                {{ __('Mark All as Read') }}
                            </x-secondary-button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy-all') }}" onsubmit="return confirm('Are you sure you want to delete all notifications?');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit">
                            {{ __('Delete All') }}
                        </x-danger-button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($notifications->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                            <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="flex items-start gap-4 p-4 rounded-lg {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50 border border-blue-200' }}">
                                    <!-- Icon based on type -->
                                    <div class="flex-shrink-0">
                                        @php
                                            $type = $notification->data['type'] ?? 'default';
                                        @endphp
                                        @if($type === 'task_status_change_requested')
                                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                        @elseif($type === 'task_status_request_reviewed')
                                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                        @elseif($type === 'task_assigned')
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                @if($type === 'task_status_change_requested')
                                                    <p class="text-sm font-medium text-gray-900">
                                                        Status Change Request
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $notification->data['requester_name'] ?? 'Someone' }} requested to change 
                                                        "{{ $notification->data['task_title'] ?? 'a task' }}" from 
                                                        <span class="font-medium">{{ $notification->data['current_status'] ?? '?' }}</span> to 
                                                        <span class="font-medium">{{ $notification->data['requested_status'] ?? '?' }}</span>
                                                    </p>
                                                @elseif($type === 'task_status_request_reviewed')
                                                    <p class="text-sm font-medium text-gray-900">
                                                        Status Request {{ ucfirst($notification->data['approval_status'] ?? 'reviewed') }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        Your request to change "{{ $notification->data['task_title'] ?? 'a task' }}" was 
                                                        <span class="font-medium {{ ($notification->data['approval_status'] ?? '') === 'approved' ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $notification->data['approval_status'] ?? 'reviewed' }}
                                                        </span>
                                                    </p>
                                                @elseif($type === 'task_assigned')
                                                    <p class="text-sm font-medium text-gray-900">
                                                        New Task Assigned
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        You have been assigned to "{{ $notification->data['task_title'] ?? 'a task' }}"
                                                    </p>
                                                @else
                                                    <p class="text-sm font-medium text-gray-900">
                                                        Notification
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ json_encode($notification->data) }}
                                                    </p>
                                                @endif
                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex-shrink-0 flex items-center gap-2">
                                        <a href="{{ route('notifications.read', $notification->id) }}" 
                                           class="text-sm text-indigo-600 hover:text-indigo-900">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
