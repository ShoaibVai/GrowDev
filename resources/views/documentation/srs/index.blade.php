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

            <!-- Controls -->
            <x-list-controls :route="route('documentation.srs.index')" :query="request()->q" :sort="request()->sort" :view="request()->view ?? 'grid'">
                <a href="{{ route('documentation.srs.create') }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                    + {{ __('Create New SRS') }}
                </a>
            </x-list-controls>

            <!-- SRS Documents List -->
            @if ($srsDocuments->count())
                @if(request('view') == 'list')
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requirements</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($srsDocuments as $srs)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $srs->title }}</div>
                                            <div class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($srs->description, 100) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $srs->functionalRequirements->count() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $srs->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('documentation.srs.edit', $srs) }}" class="text-blue-600 hover:underline">Edit</a>
                                            <a href="{{ route('documentation.srs.pdf', $srs) }}" class="text-green-600 hover:underline ml-4">PDF</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($srsDocuments as $srs)
                            <x-srs-card :srs="$srs" />
                        @endforeach
                    </div>
                @endif
        @else
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <div class="mx-auto mb-4 w-24 h-24 flex items-center justify-center bg-indigo-50 rounded-full">
                    <svg class="w-12 h-12 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-gray-600 text-lg mb-4">No SRS documents yet.</p>
                <a href="{{ route('documentation.srs.create') }}" 
                   class="inline-block px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Create Your First SRS
                </a>
            </div>
            @endif
            <div class="mt-6">
                {{ $srsDocuments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
