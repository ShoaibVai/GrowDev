<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-green-600']) }}>
    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $sdd->title }}</h3>
    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $sdd->description }}</p>

    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
        <span>{{ $sdd->components->count() }} Components</span>
        <span>{{ $sdd->created_at->format('M d, Y') }}</span>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('documentation.sdd.edit', $sdd) }}" 
           class="flex-1 px-3 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition text-center" aria-label="Edit {{ $sdd->title }}" title="Edit {{ $sdd->title }}">
            ✏️ Edit
        </a>
        <a href="{{ route('documentation.sdd.pdf', $sdd) }}" 
           class="flex-1 px-3 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition text-center" aria-label="Download PDF for {{ $sdd->title }}" title="Download PDF for {{ $sdd->title }}">
            📥 PDF
        </a>
        <form method="POST" action="{{ route('documentation.sdd.destroy', $sdd) }}" style="flex: 1;" aria-label="Delete {{ $sdd->title }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" 
                    class="w-full px-3 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                🗑️ Delete
            </button>
        </form>
    </div>
</div>
