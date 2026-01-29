@props(['type' => 'card'])

@if($type === 'card')
    <div class="bg-white rounded-lg shadow-md p-6 animate-pulse">
        <div class="flex items-start gap-4">
            <div class="skeleton-circle"></div>
            <div class="flex-1 space-y-3">
                <div class="skeleton-text w-3/4"></div>
                <div class="skeleton-text w-full"></div>
                <div class="skeleton-text w-5/6"></div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex gap-3">
                <div class="skeleton h-10 flex-1 rounded-lg"></div>
                <div class="skeleton h-10 flex-1 rounded-lg"></div>
            </div>
        </div>
    </div>

@elseif($type === 'list')
    <div class="space-y-3">
        @for($i = 0; $i < 5; $i++)
            <div class="bg-white rounded-lg shadow p-4 animate-pulse">
                <div class="flex items-center gap-3">
                    <div class="skeleton-circle w-8 h-8"></div>
                    <div class="flex-1 space-y-2">
                        <div class="skeleton-text w-2/3"></div>
                        <div class="skeleton-text w-1/2"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>

@elseif($type === 'table')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="animate-pulse">
            @for($i = 0; $i < 5; $i++)
                <div class="flex items-center gap-4 p-4 border-b border-gray-100">
                    <div class="skeleton-circle w-10 h-10"></div>
                    <div class="flex-1 space-y-2">
                        <div class="skeleton-text w-1/4"></div>
                        <div class="skeleton-text w-1/2"></div>
                    </div>
                    <div class="skeleton w-20 h-8 rounded"></div>
                </div>
            @endfor
        </div>
    </div>

@elseif($type === 'stats')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-white rounded-lg shadow p-4 animate-pulse">
                <div class="flex items-center gap-4">
                    <div class="skeleton-circle"></div>
                    <div class="flex-1 space-y-2">
                        <div class="skeleton-text w-3/4"></div>
                        <div class="skeleton h-8 w-1/2 rounded"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>

@else
    {{-- Default skeleton --}}
    <div class="bg-white rounded-lg shadow p-6 animate-pulse">
        <div class="space-y-3">
            <div class="skeleton-text w-1/2"></div>
            <div class="skeleton-text w-full"></div>
            <div class="skeleton-text w-3/4"></div>
        </div>
    </div>
@endif
