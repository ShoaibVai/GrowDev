<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('Create SRS Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('documentation.srs.store') }}" class="space-y-8">
                @csrf

                <!-- Section 1: Introduction -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">1.</span> Introduction
                    </h2>

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.1</span> Document Title *
                        </label>
                        <input type="text" id="title" name="title" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="e.g., E-Commerce Platform Software Requirements Specification"
                               value="{{ old('title') }}">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.2</span> Purpose
                        </label>
                        <textarea id="purpose" name="purpose" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe the purpose of this SRS document and the software product...">{{ old('purpose') }}</textarea>
                        @error('purpose') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="document_conventions" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.3</span> Document Conventions
                        </label>
                        <textarea id="document_conventions" name="document_conventions" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe any standards or typographical conventions used...">{{ old('document_conventions') }}</textarea>
                        @error('document_conventions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="intended_audience" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.4</span> Intended Audience and Reading Suggestions
                        </label>
                        <textarea id="intended_audience" name="intended_audience" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="List the different types of readers and suggest which sections are most relevant to each...">{{ old('intended_audience') }}</textarea>
                        @error('intended_audience') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="product_scope" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.5</span> Product Scope
                        </label>
                        <textarea id="product_scope" name="product_scope" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Provide a short description of the software, its objectives, and how it supports business goals...">{{ old('product_scope') }}</textarea>
                        @error('product_scope') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="references" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.6</span> References
                        </label>
                        <textarea id="references" name="references" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="List any other documents or web addresses this SRS refers to...">{{ old('references') }}</textarea>
                        @error('references') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Section 2: Overall Description -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">2.</span> Overall Description
                    </h2>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.1</span> Product Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Brief description of the product/project...">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="product_perspective" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.2</span> Product Perspective
                        </label>
                        <textarea id="product_perspective" name="product_perspective" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe how the product fits into the larger system context...">{{ old('product_perspective') }}</textarea>
                        @error('product_perspective') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="product_features" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.3</span> Product Features
                        </label>
                        <textarea id="product_features" name="product_features" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Summarize the major features and capabilities of the software...">{{ old('product_features') }}</textarea>
                        @error('product_features') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="user_classes" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.4</span> User Classes and Characteristics
                        </label>
                        <textarea id="user_classes" name="user_classes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Identify the various user classes that will use the system...">{{ old('user_classes') }}</textarea>
                        @error('user_classes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="operating_environment" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.5</span> Operating Environment
                        </label>
                        <textarea id="operating_environment" name="operating_environment" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe the environment in which the software will operate...">{{ old('operating_environment') }}</textarea>
                        @error('operating_environment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="design_constraints" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.6</span> Design and Implementation Constraints
                        </label>
                        <textarea id="design_constraints" name="design_constraints" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe any items that will limit developer options...">{{ old('design_constraints') }}</textarea>
                        @error('design_constraints') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="constraints" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.7</span> Constraints
                            </label>
                            <textarea id="constraints" name="constraints" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="List any project constraints...">{{ old('constraints') }}</textarea>
                            @error('constraints') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="assumptions" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.8</span> Assumptions and Dependencies
                            </label>
                            <textarea id="assumptions" name="assumptions" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="List any assumptions and dependencies...">{{ old('assumptions') }}</textarea>
                            @error('assumptions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: External Interface Requirements (Preview) -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">3.</span> External Interface Requirements
                    </h2>
                    <div class="mb-6">
                        <label for="external_interfaces" class="block text-sm font-medium text-gray-700 mb-2">
                            External Interfaces Description
                        </label>
                        <textarea id="external_interfaces" name="external_interfaces" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe user interfaces, hardware interfaces, software interfaces, and communication interfaces...">{{ old('external_interfaces') }}</textarea>
                        @error('external_interfaces') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <p class="text-sm text-gray-500 italic">
                        💡 Detailed Functional Requirements (Section 4) and Non-Functional Requirements (Section 5) 
                        can be added after creating the document.
                    </p>
                </div>

                <!-- Document Metadata -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        📄 Document Metadata
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="version" class="block text-sm font-medium text-gray-700 mb-2">Version</label>
                            <input type="text" id="version" name="version" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   value="{{ old('version', '1.0') }}"
                                   placeholder="1.0">
                            @error('version') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                                <option value="review" @selected(old('status') === 'review')>Under Review</option>
                                <option value="approved" @selected(old('status') === 'approved')>Approved</option>
                                <option value="final" @selected(old('status') === 'final')>Final</option>
                            </select>
                            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <a href="{{ route('documentation.srs.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                        ✅ Create SRS Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
