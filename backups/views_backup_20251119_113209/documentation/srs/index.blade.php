<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 {{ __('SRS Documents') }}
            </h2>
            <a href="{{ route('documentation.srs.create') }}" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                + {{ __('Create New SRS') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- SRS Documents List -->
            @if ($srsDocuments->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($srsDocuments as $srs)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-indigo-600">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $srs->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $srs->description }}</p>
                        
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>{{ $srs->functionalRequirements->count() }} Requirements</span>
                            <span>{{ $srs->created_at->format('M d, Y') }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3">
                            <a href="{{ route('documentation.srs.edit', $srs) }}" 
                               class="flex-1 px-3 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition text-center">
                                ✏️ Edit
                            </a>
                            <a href="{{ route('documentation.srs.pdf', $srs) }}" 
                               class="flex-1 px-3 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition text-center">
                                📥 PDF
                            </a>
                            <form method="POST" action="{{ route('documentation.srs.destroy', $srs) }}" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" 
                                        class="w-full px-3 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <p class="text-gray-600 text-lg mb-4">No SRS documents yet.</p>
                <a href="{{ route('documentation.srs.create') }}" 
                   class="inline-block px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Create Your First SRS
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
