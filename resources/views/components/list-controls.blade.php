@props(['route' => '', 'query' => '', 'sort' => '', 'view' => 'grid', 'extraFilters' => []])

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <form method="GET" action="{{ $route }}" class="flex items-center gap-2 flex-1" id="list-controls-form">
        <input type="search" name="q" value="{{ request()->q ?? $query }}" placeholder="Search..."
               class="border rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
               aria-label="Search">
        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Search</button>

        @foreach($extraFilters as $name => $list)
            <select name="{{ $name }}" class="ml-2 border rounded-md px-3 py-2">
                <option value="">All</option>
                @foreach($list as $val => $label)
                    <option value="{{ $val }}" {{ request($name) == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        @endforeach
        <select name="sort" onchange="document.getElementById('list-controls-form').submit()" class="ml-2 border rounded-md px-3 py-2">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
        </select>
    </form>

    <div class="flex items-center gap-2">
        <!-- sort select moved into the form above -->

        <div class="inline-flex rounded-md shadow-sm" role="tablist" aria-label="View toggle">
            <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="px-3 py-2 rounded-l-md {{ request('view', $view) == 'grid' ? 'bg-indigo-600 text-white' : 'bg-white border' }}" role="tab" aria-selected="{{ request('view', $view) == 'grid' ? 'true' : 'false' }}" title="Grid view">🔲</a>
            <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="px-3 py-2 rounded-r-md {{ request('view', $view) == 'list' ? 'bg-indigo-600 text-white' : 'bg-white border' }}" role="tab" aria-selected="{{ request('view', $view) == 'list' ? 'true' : 'false' }}" title="List view">📋</a>
        </div>
        @if(isset($slot) && trim($slot))
            <div class="ml-4">{{ $slot }}</div>
        @endif
    </div>
</div>
