<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('Create SRS Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <form method="POST" action="{{ route('documentation.srs.store') }}" class="bg-white rounded-lg shadow-md p-8">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Document Title</label>
                <input type="text" id="title" name="title" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="e.g., E-Commerce Platform SRS">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Brief description of the project..."></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Project Overview -->
            <div class="mb-6">
                <label for="project_overview" class="block text-sm font-medium text-gray-700 mb-2">Project Overview</label>
                <textarea id="project_overview" name="project_overview" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Describe the project overview..."></textarea>
                @error('project_overview') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Scope -->
            <div class="mb-6">
                <label for="scope" class="block text-sm font-medium text-gray-700 mb-2">Scope</label>
                <textarea id="scope" name="scope" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Define the scope of the project..."></textarea>
                @error('scope') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Constraints -->
            <div class="mb-6">
                <label for="constraints" class="block text-sm font-medium text-gray-700 mb-2">Constraints</label>
                <textarea id="constraints" name="constraints" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="List any constraints..."></textarea>
                @error('constraints') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Assumptions -->
            <div class="mb-6">
                <label for="assumptions" class="block text-sm font-medium text-gray-700 mb-2">Assumptions</label>
                <textarea id="assumptions" name="assumptions" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="List any assumptions..."></textarea>
                @error('assumptions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('documentation.srs.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                    ✅ Create SRS
                </button>
            </div>
        </form>
        </div>
    </div>
</x-app-layout>
