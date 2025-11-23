<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-indigo-600']) }}>
    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $srs->title }}</h3>
    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $srs->description }}</p>

    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
        <span>{{ $srs->functionalRequirements->count() }} Requirements</span>
        <span>{{ $srs->created_at->format('M d, Y') }}</span>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('documentation.srs.edit', $srs) }}" 
           class="flex-1 px-3 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition text-center" aria-label="Edit {{ $srs->title }}" title="Edit {{ $srs->title }}">
            ✏️ Edit
        </a>
        <a href="{{ route('documentation.srs.pdf', $srs) }}" 
           class="flex-1 px-3 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition text-center" aria-label="Download PDF for {{ $srs->title }}" title="Download PDF for {{ $srs->title }}">
            📥 PDF
        </a>
        <form method="POST" action="{{ route('documentation.srs.destroy', $srs) }}" style="flex: 1;" aria-label="Delete {{ $srs->title }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" 
                    class="w-full px-3 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                🗑️ Delete
            </button>
        </form>
    </div>
</div>
