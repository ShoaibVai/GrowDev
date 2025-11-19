<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏗️ {{ __('Create SDD Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('documentation.sdd.store') }}" class="bg-white rounded-lg shadow-md p-8">
                @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Document Title</label>
                <input type="text" id="title" name="title" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="e.g., E-Commerce Platform SDD">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Brief description of the design..."></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Design Overview -->
            <div class="mb-6">
                <label for="design_overview" class="block text-sm font-medium text-gray-700 mb-2">Design Overview</label>
                <textarea id="design_overview" name="design_overview" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Describe the overall design approach..."></textarea>
                @error('design_overview') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Architecture Overview -->
            <div class="mb-6">
                <label for="architecture_overview" class="block text-sm font-medium text-gray-700 mb-2">Architecture Overview</label>
                <textarea id="architecture_overview" name="architecture_overview" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Describe the software architecture..."></textarea>
                @error('architecture_overview') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('documentation.sdd.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                    ✅ Create SDD
                </button>
            </div>
            </form>
        </div>
    </div>
</x-app-layout>
